<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use App\Models\TicketComment;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;

class TicketController extends BaseController
{
    public function index()
    {
        try {
            $this->authorize('viewAny', Ticket::class);

            return $this->view('tickets.index');
        } catch (\Exception $e) {
            return $this->error('Failed to load page.', $e->getMessage());
        }
    }

    public function list(Request $request)
    {
        try {
            $this->authorize('viewAny', Ticket::class);
            $companyId = $this->getCompanyId();

            $query = Ticket::byCompany($companyId)
                ->with(['employee.user', 'assignee', 'creator']);

            if (!auth()->user()->hasRole(['Owner', 'Admin'])) {
                $employeeId = auth()->user()->employee->id;
                $query->where(function ($q) use ($employeeId) {
                    $q->where('employee_id', $employeeId)
                        ->orWhere('assigned_to', auth()->id());
                });
            }

            if ($search = $request->input('search.value')) {
                $query->where(function ($q) use ($search) {
                    $q->where('subject', 'like', "%{$search}%")
                        ->orWhere('ticket_number', 'like', "%{$search}%")
                        ->orWhere('description', 'like', "%{$search}%")
                        ->orWhereHas('employee.user', function ($uq) use ($search) {
                            $uq->where('first_name', 'like', "%{$search}%")
                                ->orWhere('last_name', 'like', "%{$search}%");
                        });
                });
            }

            if ($request->filled('status')) {
                $query->where('status', $request->status);
            }

            if ($request->filled('priority')) {
                $query->where('priority', $request->priority);
            }

            if ($request->filled('category')) {
                $query->where('category', $request->category);
            }

            return $this->datatableResponse($query, $request);
        } catch (\Exception $e) {
            return response()->json([
                'draw' => (int) $request->input('draw', 0),
                'recordsTotal' => 0,
                'recordsFiltered' => 0,
                'data' => [],
                'error' => $e->getMessage(),
            ]);
        }
    }

    public function store(Request $request)
    {
        try {
            $this->authorize('create', Ticket::class);

            $validated = $request->validate([
                'subject' => 'required|string|max:255',
                'description' => 'required|string',
                'category' => 'required|string|in:it,hr,administration,other',
                'priority' => 'required|string|in:low,medium,high,critical',
            ]);

            $ticketNumber = 'TKT-' . strtoupper(substr(uniqid(), -6));

            $ticket = Ticket::create([
                'company_id' => $this->getCompanyId(),
                'employee_id' => auth()->user()->employee->id,
                'ticket_number' => $ticketNumber,
                'created_by' => auth()->id(),
                'status' => 'open',
                ...$validated,
            ]);

            return $this->created('Ticket created successfully.', $ticket->load(['employee.user']));
        } catch (\Exception $e) {
            return $this->error('Failed to create ticket.', $e->getMessage());
        }
    }

    public function show($id)
    {
        try {
            $companyId = $this->getCompanyId();
            $ticket = Ticket::byCompany($companyId)
                ->with(['employee.user', 'assignee', 'creator', 'comments.user'])
                ->findOrFail($id);
            $this->authorize('view', $ticket);

            return $this->view('tickets.show', compact('ticket'));
        } catch (ModelNotFoundException $e) {
            return $this->error('Ticket not found.', null, 404);
        } catch (\Exception $e) {
            return $this->error('Failed to load ticket.', $e->getMessage());
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $ticket = Ticket::findOrFail($id);
            $this->authorize('update', $ticket);

            $validated = $request->validate([
                'subject' => 'required|string|max:255',
                'description' => 'required|string',
                'category' => 'required|string|in:it,hr,administration,other',
                'priority' => 'required|string|in:low,medium,high,critical',
            ]);

            $ticket->update($validated);

            return $this->updated('Ticket updated successfully.', $ticket->fresh()->load(['employee.user', 'assignee']));
        } catch (ModelNotFoundException $e) {
            return $this->error('Ticket not found.', null, 404);
        } catch (\Exception $e) {
            return $this->error('Failed to update ticket.', $e->getMessage());
        }
    }

    public function destroy($id)
    {
        try {
            $ticket = Ticket::findOrFail($id);
            $this->authorize('delete', $ticket);

            $ticket->delete();

            return $this->deleted('Ticket deleted successfully.');
        } catch (ModelNotFoundException $e) {
            return $this->error('Ticket not found.', null, 404);
        } catch (\Exception $e) {
            return $this->error('Failed to delete ticket.', $e->getMessage());
        }
    }

    public function assign(Request $request, $id)
    {
        try {
            $ticket = Ticket::findOrFail($id);
            $this->authorize('assign', $ticket);

            $request->validate(['assigned_to' => 'required|exists:users,id']);

            $ticket->update([
                'assigned_to' => $request->assigned_to,
                'status' => $ticket->status === 'open' ? 'in_progress' : $ticket->status,
            ]);

            return $this->success('Ticket assigned successfully.', $ticket->fresh()->load(['assignee']));
        } catch (ModelNotFoundException $e) {
            return $this->error('Ticket not found.', null, 404);
        } catch (\Exception $e) {
            return $this->error('Failed to assign ticket.', $e->getMessage());
        }
    }

    public function addComment(Request $request, $id)
    {
        try {
            $ticket = Ticket::findOrFail($id);
            $this->authorize('view', $ticket);

            $validated = $request->validate([
                'comment' => 'required|string',
                'is_internal' => 'nullable|boolean',
            ]);

            $comment = TicketComment::create([
                'ticket_id' => $ticket->id,
                'user_id' => auth()->id(),
                'created_by' => auth()->id(),
                'is_internal' => $request->boolean('is_internal', false),
                'comment' => $validated['comment'],
            ]);

            return $this->success('Comment added.', $comment->load(['user']));
        } catch (ModelNotFoundException $e) {
            return $this->error('Ticket not found.', null, 404);
        } catch (\Exception $e) {
            return $this->error('Failed to add comment.', $e->getMessage());
        }
    }

    public function resolve($id)
    {
        try {
            $ticket = Ticket::findOrFail($id);
            $this->authorize('update', $ticket);

            $ticket->update([
                'status' => 'resolved',
                'resolved_at' => now(),
            ]);

            return $this->success('Ticket resolved.', $ticket->fresh());
        } catch (ModelNotFoundException $e) {
            return $this->error('Ticket not found.', null, 404);
        } catch (\Exception $e) {
            return $this->error('Failed to resolve ticket.', $e->getMessage());
        }
    }

    public function close($id)
    {
        try {
            $ticket = Ticket::findOrFail($id);
            $this->authorize('update', $ticket);

            $ticket->update([
                'status' => 'closed',
                'closed_at' => now(),
            ]);

            return $this->success('Ticket closed.', $ticket->fresh());
        } catch (ModelNotFoundException $e) {
            return $this->error('Ticket not found.', null, 404);
        } catch (\Exception $e) {
            return $this->error('Failed to close ticket.', $e->getMessage());
        }
    }

    public function reopen($id)
    {
        try {
            $ticket = Ticket::findOrFail($id);
            $this->authorize('update', $ticket);

            $ticket->update([
                'status' => 'open',
                'resolved_at' => null,
                'closed_at' => null,
            ]);

            return $this->success('Ticket reopened.', $ticket->fresh());
        } catch (ModelNotFoundException $e) {
            return $this->error('Ticket not found.', null, 404);
        } catch (\Exception $e) {
            return $this->error('Failed to reopen ticket.', $e->getMessage());
        }
    }
}
