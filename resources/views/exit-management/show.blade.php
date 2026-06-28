@php
    $isAdmin = auth()->user()->hasRole(['Owner', 'Admin']);
@endphp

<ul class="nav nav-tabs mb-3" id="exitTabs" role="tablist">
    <li class="nav-item" role="presentation">
        <button class="nav-link active" id="details-tab" data-bs-toggle="tab" data-bs-target="#details" type="button">Details</button>
    </li>
    <li class="nav-item" role="presentation">
        <button class="nav-link" id="clearance-tab" data-bs-toggle="tab" data-bs-target="#clearance" type="button">Clearance Checklist</button>
    </li>
    <li class="nav-item" role="presentation">
        <button class="nav-link" id="interview-tab" data-bs-toggle="tab" data-bs-target="#interview" type="button">Exit Interview</button>
    </li>
</ul>

<div class="tab-content">
    <div class="tab-pane fade show active" id="details">
        <div class="table-responsive">
            <table class="table table-bordered">
                <tr>
                    <th class="w-25">Employee</th>
                    <td>{{ $resignation->employee->full_name }}</td>
                </tr>
                <tr>
                    <th>Notice Date</th>
                    <td>{{ $resignation->notice_date?->format('d/m/Y') }}</td>
                </tr>
                <tr>
                    <th>Last Working Day</th>
                    <td>{{ $resignation->last_working_date?->format('d/m/Y') ?? '-' }}</td>
                </tr>
                <tr>
                    <th>Status</th>
                    <td><span class="badge bg-{{ ['pending'=>'warning','approved'=>'success','rejected'=>'danger','cancelled'=>'secondary','cleared'=>'info'][$resignation->status] ?? 'secondary' }}">{{ ucfirst($resignation->status) }}</span></td>
                </tr>
                <tr>
                    <th>Reason Category</th>
                    <td>{{ $resignation->reason_category ? ucfirst($resignation->reason_category) : '-' }}</td>
                </tr>
                <tr>
                    <th>Reason</th>
                    <td>{{ $resignation->reason ?? '-' }}</td>
                </tr>
                <tr>
                    <th>Notice Period</th>
                    <td>{{ $resignation->notice_period_days ? $resignation->notice_period_days . ' days' : '-' }}</td>
                </tr>
                <tr>
                    <th>Accrued Leave Payout</th>
                    <td>{{ $resignation->accrued_leave_payout ? number_format($resignation->accrued_leave_payout, 2) : '-' }}</td>
                </tr>
                @if($resignation->approved_by)
                <tr>
                    <th>Approved By</th>
                    <td>{{ $resignation->approver?->first_name }} {{ $resignation->approver?->last_name }}</td>
                </tr>
                <tr>
                    <th>Approved At</th>
                    <td>{{ $resignation->approved_at?->format('d/m/Y H:i') }}</td>
                </tr>
                @endif
                @if($resignation->rejection_reason)
                <tr>
                    <th>Rejection Reason</th>
                    <td>{{ $resignation->rejection_reason }}</td>
                </tr>
                @endif
            </table>
        </div>
    </div>

    <div class="tab-pane fade" id="clearance">
        <div class="d-flex justify-content-between mb-3">
            <h6 class="mb-0">Clearance Items</h6>
            @if($isAdmin)
            <button class="btn btn-sm btn-primary" onclick="addClearanceItem({{ $resignation->id }})">
                <i class="bi bi-plus"></i> Add Item
            </button>
            @endif
        </div>
        <div class="table-responsive">
            <table class="table table-sm">
                <thead>
                    <tr>
                        <th>Department</th>
                        <th>Item</th>
                        <th>Assigned To</th>
                        <th>Status</th>
                        <th>Cleared By</th>
                        <th>Notes</th>
                        @if($isAdmin)<th>Action</th>@endif
                    </tr>
                </thead>
                <tbody>
                    @forelse($resignation->clearanceItems as $item)
                    <tr>
                        <td>{{ $item->department }}</td>
                        <td>{{ $item->item }}</td>
                        <td>{{ $item->assignee?->first_name }} {{ $item->assignee?->last_name ?? '-' }}</td>
                        <td>
                            @if($item->is_cleared)
                                <span class="badge bg-success">Cleared</span>
                            @else
                                <span class="badge bg-warning">Pending</span>
                            @endif
                        </td>
                        <td>{{ $item->clearer?->first_name }} {{ $item->clearer?->last_name ?? '-' }}</td>
                        <td>{{ $item->notes ?? '-' }}</td>
                        @if($isAdmin)
                        <td>
                            <button class="btn btn-sm {{ $item->is_cleared ? 'btn-warning' : 'btn-success' }}" onclick="toggleClearItem({{ $item->id }}, {{ $item->is_cleared ? 'false' : 'true' }})">
                                {{ $item->is_cleared ? 'Undo' : 'Clear' }}
                            </button>
                        </td>
                        @endif
                    </tr>
                    @empty
                    <tr>
                        <td colspan="{{ $isAdmin ? 7 : 6 }}" class="text-muted">No clearance items added yet.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="tab-pane fade" id="interview">
        @php $interview = $resignation->exitInterview; @endphp
        @if($interview)
        <div class="table-responsive">
            <table class="table table-bordered">
                <tr>
                    <th class="w-25">Interview Date</th>
                    <td>{{ $interview->interview_date?->format('d/m/Y') ?? '-' }}</td>
                </tr>
                <tr>
                    <th>Interviewed By</th>
                    <td>{{ $interview->interviewer?->first_name }} {{ $interview->interviewer?->last_name ?? '-' }}</td>
                </tr>
                <tr>
                    <th>Overall Experience</th>
                    <td>{{ $interview->overall_experience ?? '-' }}</td>
                </tr>
                <tr>
                    <th>Reason for Leaving</th>
                    <td>{{ $interview->reason_for_leaving ?? '-' }}</td>
                </tr>
                <tr>
                    <th>Feedback on Company</th>
                    <td>{{ $interview->feedback_on_company ?? '-' }}</td>
                </tr>
                <tr>
                    <th>Would Recommend</th>
                    <td>
                        @if($interview->would_recommend === true)
                            <span class="badge bg-success">Yes</span>
                        @elseif($interview->would_recommend === false)
                            <span class="badge bg-danger">No</span>
                        @else
                            -
                        @endif
                    </td>
                </tr>
                <tr>
                    <th>Suggestions</th>
                    <td>{{ $interview->suggestions ?? '-' }}</td>
                </tr>
            </table>
        </div>
        @endif
        @if($isAdmin)
        <button class="btn btn-primary btn-sm" onclick="editInterview({{ $resignation->id }})">
            <i class="bi bi-pencil"></i> {{ $interview ? 'Edit' : 'Add' }} Exit Interview
        </button>
        @endif
    </div>
</div>

@push('scripts')
<script>
    function toggleClearItem(itemId, cleared) {
        Swal.fire({
            title: cleared ? 'Clear this item?' : 'Undo clearance?',
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: 'Yes',
            cancelButtonText: 'Cancel'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: '{{ url("exit-management") }}/clear-item/' + itemId,
                    type: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}',
                        is_cleared: cleared
                    },
                    success: function () {
                        location.reload();
                    },
                    error: function () {
                        App.toast('Error updating clearance item', 'error');
                    }
                });
            }
        });
    }

    function addClearanceItem(resignationId) {
        const html = `
            <form id="clearanceItemForm">
                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label">Department <span class="text-danger">*</span></label>
                        <select name="department" class="form-select" required>
                            <option value="">Select</option>
                            <option value="IT">IT</option>
                            <option value="HR">HR</option>
                            <option value="Finance">Finance</option>
                            <option value="Operations">Operations</option>
                            <option value="Admin">Admin</option>
                            <option value="Legal">Legal</option>
                            <option value="Other">Other</option>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Item <span class="text-danger">*</span></label>
                        <input type="text" name="item" class="form-control" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Assigned To</label>
                        <select name="assigned_to" class="form-select">
                            <option value="">Unassigned</option>
                        </select>
                    </div>
                </div>
            </form>
        `;
        $('#globalModalLabel').text('Add Clearance Item');
        $('#globalModalBody').html(html);
        $('#globalModal').modal('show');
    }

    function editInterview(resignationId) {
        const interview = @json($resignation->exitInterview);
        const html = `
            <form id="interviewForm">
                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label">Interview Date</label>
                        <input type="text" name="interview_date" class="form-control flatpickr" value="${interview?.interview_date || ''}">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Interviewed By</label>
                        <select name="interviewed_by" class="form-select">
                            <option value="">Select</option>
                        </select>
                    </div>
                    <div class="col-12">
                        <label class="form-label">Overall Experience</label>
                        <textarea name="overall_experience" class="form-control" rows="2">${interview?.overall_experience || ''}</textarea>
                    </div>
                    <div class="col-12">
                        <label class="form-label">Reason for Leaving</label>
                        <textarea name="reason_for_leaving" class="form-control" rows="2">${interview?.reason_for_leaving || ''}</textarea>
                    </div>
                    <div class="col-12">
                        <label class="form-label">Feedback on Company</label>
                        <textarea name="feedback_on_company" class="form-control" rows="2">${interview?.feedback_on_company || ''}</textarea>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Would Recommend</label>
                        <select name="would_recommend" class="form-select">
                            <option value="">N/A</option>
                            <option value="1" ${interview?.would_recommend ? 'selected' : ''}>Yes</option>
                            <option value="0" ${interview?.would_recommend === false ? 'selected' : ''}>No</option>
                        </select>
                    </div>
                    <div class="col-12">
                        <label class="form-label">Suggestions</label>
                        <textarea name="suggestions" class="form-control" rows="2">${interview?.suggestions || ''}</textarea>
                    </div>
                </div>
            </form>
        `;
        $('#globalModalLabel').text(interview ? 'Edit Exit Interview' : 'Add Exit Interview');
        $('#globalModalBody').html(html);
        $('#globalModal').modal('show');
        flatpickr('.flatpickr', { dateFormat: 'Y-m-d', allowInput: true });

        loadUsers();
    }

    function loadUsers() {
        $.get('{{ url("employees/list") }}', function (data) {
            const selects = document.querySelectorAll('select[name="assigned_to"], select[name="interviewed_by"]');
            selects.forEach(sel => {
                data.data?.forEach(u => {
                    sel.innerHTML += `<option value="${u.user_id || u.id}">${u.full_name || u.first_name + ' ' + u.last_name}</option>`;
                });
            });
        });
    }

    $(document).on('submit', '#interviewForm', function (e) {
        e.preventDefault();
        const formData = $(this).serialize();
        $.ajax({
            url: '{{ url("exit-management") }}/' + {{ $resignation->id }} + '/interview',
            type: 'POST',
            data: formData,
            success: function () {
                $('#globalModal').modal('hide');
                location.reload();
                App.toast('Exit interview saved', 'success');
            },
            error: function () {
                App.toast('Error saving exit interview', 'error');
            }
        });
    });
</script>
@endpush
