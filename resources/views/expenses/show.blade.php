<div class="row g-3">
    <div class="col-md-6">
        <table class="table table-sm table-borderless mb-0">
            <tr>
                <td class="fw-semibold text-muted" style="width: 140px;">Employee</td>
                <td>{{ $expense->employee->full_name ?? '-' }}</td>
            </tr>
            <tr>
                <td class="fw-semibold text-muted">Category</td>
                <td>{{ $expense->category->name ?? '-' }}</td>
            </tr>
            <tr>
                <td class="fw-semibold text-muted">Expense Date</td>
                <td>{{ $expense->expense_date ? $expense->expense_date->format('d/m/Y') : '-' }}</td>
            </tr>
            <tr>
                <td class="fw-semibold text-muted">Amount</td>
                <td>{{ currency($expense->amount) }}</td>
            </tr>
            <tr>
                <td class="fw-semibold text-muted">Status</td>
                <td>
                    @php $badges = ['draft' => 'secondary', 'pending' => 'warning', 'approved' => 'success', 'rejected' => 'danger', 'paid' => 'info']; @endphp
                    <span class="badge bg-{{ $badges[$expense->status] ?? 'secondary' }}">{{ ucfirst($expense->status) }}</span>
                </td>
            </tr>
        </table>
    </div>
    <div class="col-md-6">
        <table class="table table-sm table-borderless mb-0">
            <tr>
                <td class="fw-semibold text-muted" style="width: 140px;">Description</td>
                <td>{{ $expense->description }}</td>
            </tr>
            <tr>
                <td class="fw-semibold text-muted">Receipt</td>
                <td>
                    @if($expense->receipt_path)
                        <a href="{{ Storage::url($expense->receipt_path) }}" target="_blank" class="btn btn-sm btn-outline-primary">
                            <i class="bi bi-eye"></i> View Receipt
                        </a>
                    @else
                        -
                    @endif
                </td>
            </tr>
            @if($expense->approver)
            <tr>
                <td class="fw-semibold text-muted">Approved By</td>
                <td>{{ $expense->approver->first_name }} {{ $expense->approver->last_name }}</td>
            </tr>
            @endif
            @if($expense->approved_at)
            <tr>
                <td class="fw-semibold text-muted">Approved At</td>
                <td>{{ $expense->approved_at->format('d/m/Y H:i') }}</td>
            </tr>
            @endif
            @if($expense->paid_at)
            <tr>
                <td class="fw-semibold text-muted">Paid At</td>
                <td>{{ $expense->paid_at->format('d/m/Y H:i') }}</td>
            </tr>
            @endif
            @if($expense->rejection_reason)
            <tr>
                <td class="fw-semibold text-muted">Rejection Reason</td>
                <td class="text-danger">{{ $expense->rejection_reason }}</td>
            </tr>
            @endif
            <tr>
                <td class="fw-semibold text-muted">Notes</td>
                <td>{{ $expense->notes ?? '-' }}</td>
            </tr>
        </table>
    </div>
</div>
<div class="text-end mt-3">
    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
</div>
