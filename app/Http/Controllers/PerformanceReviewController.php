<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\PerformanceGoal;
use App\Models\PerformanceReview;
use App\Models\RatingScale;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PerformanceReviewController extends BaseController
{
    public function create()
    {
        try {
            $this->authorize('create', PerformanceReview::class);
            $companyId = $this->getCompanyId();
            $employees = Employee::byCompany($companyId)->with('user')->get();

            return $this->view('performance-reviews.create', compact('employees'));
        } catch (\Exception $e) {
            return $this->error('Failed to load form.', $e->getMessage());
        }
    }

    public function index()
    {
        try {
            $this->authorize('viewAny', PerformanceReview::class);
            $companyId = $this->getCompanyId();

            $employees = Employee::byCompany($companyId)->with('user')->get();
            $ratingScales = RatingScale::byCompany($companyId)->active()->get();

            return $this->view('performance-reviews.index', compact('employees', 'ratingScales'));
        } catch (\Exception $e) {
            return $this->error('Failed to load page.', $e->getMessage());
        }
    }

    public function list(Request $request)
    {
        try {
            $this->authorize('viewAny', PerformanceReview::class);
            $companyId = $this->getCompanyId();

            $query = PerformanceReview::byCompany($companyId)
                ->with(['employee.user', 'reviewer', 'creator'])
                ->withCount('goals');

            if (auth()->user()->employee && !auth()->user()->hasRole(['Owner', 'Admin'])) {
                $query->where('employee_id', auth()->user()->employee->id);
            }

            if ($search = $request->input('search.value')) {
                $query->where(function ($q) use ($search) {
                    $q->where('review_period', 'like', "%{$search}%")
                        ->orWhereHas('employee.user', function ($uq) use ($search) {
                            $uq->where('first_name', 'like', "%{$search}%")
                                ->orWhere('last_name', 'like', "%{$search}%");
                        });
                });
            }

            if ($request->filled('status')) {
                $query->where('status', $request->status);
            }

            if ($request->filled('employee_id')) {
                $query->where('employee_id', $request->employee_id);
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
            $this->authorize('create', PerformanceReview::class);

            $validated = $request->validate([
                'employee_id' => 'required|exists:employees,id',
                'reviewer_id' => 'nullable|exists:users,id',
                'review_period' => 'required|string|max:255',
                'start_date' => 'required|date',
                'end_date' => 'required|date|after_or_equal:start_date',
                'due_date' => 'nullable|date|after_or_equal:start_date',
                'employee_notes' => 'nullable|string',
            ]);

            $review = PerformanceReview::create([
                'company_id' => $this->getCompanyId(),
                'created_by' => auth()->id(),
                'status' => 'draft',
                ...$validated,
            ]);

            return $this->created('Performance review created successfully.', $review->load(['employee.user', 'reviewer']));
        } catch (\Exception $e) {
            return $this->error('Failed to create review.', $e->getMessage());
        }
    }

    public function show($id)
    {
        try {
            $companyId = $this->getCompanyId();
            $review = PerformanceReview::byCompany($companyId)
                ->with(['employee.user', 'reviewer', 'goals', 'feedbacks.reviewer', 'creator'])
                ->withCount('goals')
                ->findOrFail($id);
            $this->authorize('view', $review);

            return $this->view('performance-reviews.show', compact('review'));
        } catch (ModelNotFoundException $e) {
            return $this->error('Review not found.', null, 404);
        } catch (\Exception $e) {
            return $this->error('Failed to load review.', $e->getMessage());
        }
    }

    public function edit($id)
    {
        try {
            $companyId = $this->getCompanyId();
            $review = PerformanceReview::byCompany($companyId)->with(['employee.user', 'reviewer'])->findOrFail($id);
            $this->authorize('update', $review);

            $employees = Employee::byCompany($companyId)->with('user')->get();

            return $this->view('performance-reviews.create', compact('review', 'employees'));
        } catch (ModelNotFoundException $e) {
            return $this->error('Review not found.', null, 404);
        } catch (\Exception $e) {
            return $this->error('Failed to load form.', $e->getMessage());
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $review = PerformanceReview::findOrFail($id);
            $this->authorize('update', $review);

            $validated = $request->validate([
                'employee_id' => 'required|exists:employees,id',
                'reviewer_id' => 'nullable|exists:users,id',
                'review_period' => 'required|string|max:255',
                'start_date' => 'required|date',
                'end_date' => 'required|date|after_or_equal:start_date',
                'due_date' => 'nullable|date|after_or_equal:start_date',
                'employee_notes' => 'nullable|string',
                'reviewer_notes' => 'nullable|string',
                'status' => 'nullable|in:draft,pending_review,completed,cancelled',
            ]);

            $review->update($validated);

            return $this->updated('Review updated successfully.', $review->load(['employee.user', 'reviewer']));
        } catch (ModelNotFoundException $e) {
            return $this->error('Review not found.', null, 404);
        } catch (\Exception $e) {
            return $this->error('Failed to update review.', $e->getMessage());
        }
    }

    public function destroy($id)
    {
        try {
            $review = PerformanceReview::findOrFail($id);
            $this->authorize('delete', $review);

            $review->delete();

            return $this->deleted('Review deleted successfully.');
        } catch (ModelNotFoundException $e) {
            return $this->error('Review not found.', null, 404);
        } catch (\Exception $e) {
            return $this->error('Failed to delete review.', $e->getMessage());
        }
    }

    public function goals(Request $request, $id)
    {
        try {
            $review = PerformanceReview::findOrFail($id);
            $this->authorize('update', $review);

            if ($request->isMethod('post')) {
                $validated = $request->validate([
                    'title' => 'required|string|max:255',
                    'description' => 'nullable|string',
                    'category' => 'required|in:kpi,objective,development',
                    'target_value' => 'nullable|string|max:255',
                    'achieved_value' => 'nullable|string|max:255',
                    'weight' => 'nullable|numeric|min:0|max:100',
                    'self_rating' => 'nullable|integer|min:1|max:5',
                    'manager_rating' => 'nullable|integer|min:1|max:5',
                    'status' => 'nullable|in:not_started,in_progress,achieved,not_achieved',
                ]);

                $goal = $review->goals()->create($validated);

                return $this->created('Goal added successfully.', $goal);
            }

            if ($request->isMethod('put') || $request->isMethod('patch')) {
                $goalId = $request->input('goal_id');
                $goal = PerformanceGoal::where('review_id', $review->id)->findOrFail($goalId);

                $validated = $request->validate([
                    'title' => 'required|string|max:255',
                    'description' => 'nullable|string',
                    'category' => 'required|in:kpi,objective,development',
                    'target_value' => 'nullable|string|max:255',
                    'achieved_value' => 'nullable|string|max:255',
                    'weight' => 'nullable|numeric|min:0|max:100',
                    'self_rating' => 'nullable|integer|min:1|max:5',
                    'manager_rating' => 'nullable|integer|min:1|max:5',
                    'status' => 'nullable|in:not_started,in_progress,achieved,not_achieved',
                ]);

                $goal->update($validated);

                return $this->updated('Goal updated successfully.', $goal);
            }

            if ($request->isMethod('delete')) {
                $goalId = $request->input('goal_id');
                $goal = PerformanceGoal::where('review_id', $review->id)->findOrFail($goalId);
                $goal->delete();

                return $this->deleted('Goal deleted successfully.');
            }

            return $this->error('Invalid request method.');
        } catch (ModelNotFoundException $e) {
            return $this->error('Resource not found.', null, 404);
        } catch (\Exception $e) {
            return $this->error('Failed to manage goals.', $e->getMessage());
        }
    }

    public function submitForReview($id)
    {
        try {
            $review = PerformanceReview::findOrFail($id);
            $this->authorize('update', $review);

            if ($review->status !== 'draft') {
                return $this->error('Only draft reviews can be submitted for review.');
            }

            $review->update(['status' => 'pending_review']);

            return $this->success('Review submitted for review successfully.', $review->fresh()->load(['employee.user', 'reviewer']));
        } catch (ModelNotFoundException $e) {
            return $this->error('Review not found.', null, 404);
        } catch (\Exception $e) {
            return $this->error('Failed to submit review.', $e->getMessage());
        }
    }

    public function completeReview(Request $request, $id)
    {
        try {
            $review = PerformanceReview::findOrFail($id);
            $this->authorize('update', $review);

            if ($review->status !== 'pending_review') {
                return $this->error('Only reviews in pending review can be completed.');
            }

            $validated = $request->validate([
                'overall_rating' => 'required|numeric|min:0|max:999.99',
                'reviewer_notes' => 'nullable|string',
            ]);

            $review->update([
                'overall_rating' => $validated['overall_rating'],
                'reviewer_notes' => $validated['reviewer_notes'] ?? $review->reviewer_notes,
                'status' => 'completed',
            ]);

            return $this->success('Review completed successfully.', $review->fresh()->load(['employee.user', 'reviewer']));
        } catch (ModelNotFoundException $e) {
            return $this->error('Review not found.', null, 404);
        } catch (\Exception $e) {
            return $this->error('Failed to complete review.', $e->getMessage());
        }
    }
}
