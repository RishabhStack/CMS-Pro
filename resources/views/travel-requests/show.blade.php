<div class="card">
    <div class="card-body">
        <div class="row g-3">
            <div class="col-md-6">
                <label class="fw-semibold text-muted small text-uppercase">Employee</label>
                <p class="mb-0 fs-6">{{ $travelRequest->employee->full_name ?? 'N/A' }}</p>
            </div>
            <div class="col-md-6">
                <label class="fw-semibold text-muted small text-uppercase">Destination</label>
                <p class="mb-0 fs-6">{{ $travelRequest->destination }}</p>
            </div>
            <div class="col-md-4">
                <label class="fw-semibold text-muted small text-uppercase">From Date</label>
                <p class="mb-0 fs-6">{{ $travelRequest->from_date->format('M d, Y') }}</p>
            </div>
            <div class="col-md-4">
                <label class="fw-semibold text-muted small text-uppercase">To Date</label>
                <p class="mb-0 fs-6">{{ $travelRequest->to_date->format('M d, Y') }}</p>
            </div>
            <div class="col-md-4">
                <label class="fw-semibold text-muted small text-uppercase">Mode</label>
                <p class="mb-0 fs-6">{{ ucfirst($travelRequest->mode) }}</p>
            </div>
            <div class="col-md-4">
                <label class="fw-semibold text-muted small text-uppercase">Estimated Cost</label>
                <p class="mb-0 fs-6">{{ $travelRequest->estimated_cost ? number_format($travelRequest->estimated_cost, 2) : '-' }}</p>
            </div>
            <div class="col-md-4">
                <label class="fw-semibold text-muted small text-uppercase">Actual Cost</label>
                <p class="mb-0 fs-6">{{ $travelRequest->actual_cost ? number_format($travelRequest->actual_cost, 2) : '-' }}</p>
            </div>
            <div class="col-md-4">
                <label class="fw-semibold text-muted small text-uppercase">Status</label>
                <p class="mb-0 fs-6">
                    @php
                        $badges = ['draft' => 'secondary', 'pending' => 'warning', 'approved' => 'success', 'rejected' => 'danger', 'cancelled' => 'secondary', 'settled' => 'info'];
                    @endphp
                    <span class="badge bg-{{ $badges[$travelRequest->status] ?? 'secondary' }}">{{ ucfirst($travelRequest->status) }}</span>
                </p>
            </div>
            <div class="col-12">
                <label class="fw-semibold text-muted small text-uppercase">Purpose</label>
                <p class="mb-0 fs-6">{{ $travelRequest->purpose }}</p>
            </div>
            @if($travelRequest->notes)
                <div class="col-12">
                    <label class="fw-semibold text-muted small text-uppercase">Notes</label>
                    <p class="mb-0 fs-6">{{ $travelRequest->notes }}</p>
                </div>
            @endif
            @if($travelRequest->approved_by)
                <div class="col-md-6">
                    <label class="fw-semibold text-muted small text-uppercase">Approved By</label>
                    <p class="mb-0 fs-6">{{ $travelRequest->approver->first_name ?? 'N/A' }} {{ $travelRequest->approver->last_name ?? '' }}</p>
                </div>
                <div class="col-md-6">
                    <label class="fw-semibold text-muted small text-uppercase">Approved At</label>
                    <p class="mb-0 fs-6">{{ $travelRequest->approved_at ? $travelRequest->approved_at->format('M d, Y h:i A') : '-' }}</p>
                </div>
            @endif
        </div>

        @if($travelRequest->itineraries->count() > 0)
            <hr>
            <h6 class="fw-semibold mb-3">Itinerary</h6>
            <div class="table-responsive">
                <table class="table table-sm">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Time</th>
                            <th>Activity</th>
                            <th>Location</th>
                            <th>Details</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($travelRequest->itineraries as $item)
                            <tr>
                                <td>{{ $item->date->format('M d, Y') }}</td>
                                <td>{{ $item->time ? $item->time->format('H:i') : '-' }}</td>
                                <td>{{ $item->activity }}</td>
                                <td>{{ $item->location ?? '-' }}</td>
                                <td>{{ $item->details ?? '-' }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif

        <div class="text-end mt-3">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        </div>
    </div>
</div>
