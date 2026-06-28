<form id="resignationForm" action="{{ route('exit-management.update', $resignation->id) }}" method="POST">
    @csrf
    @method('PUT')
    <div class="row g-3">
        <div class="col-md-6">
            <label class="form-label">Notice Date <span class="text-danger">*</span></label>
            <input type="text" name="notice_date" class="form-control flatpickr" value="{{ $resignation->notice_date?->format('Y-m-d') }}" required>
        </div>
        <div class="col-md-6">
            <label class="form-label">Last Working Day</label>
            <input type="text" name="last_working_date" class="form-control flatpickr" value="{{ $resignation->last_working_date?->format('Y-m-d') }}">
        </div>
        <div class="col-md-6">
            <label class="form-label">Reason Category</label>
            <select name="reason_category" class="form-select">
                <option value="">Select</option>
                @foreach(['personal','career','health','relocation','other'] as $cat)
                    <option value="{{ $cat }}" {{ $resignation->reason_category === $cat ? 'selected' : '' }}>{{ ucfirst($cat) }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-6">
            <label class="form-label">Notice Period (Days)</label>
            <input type="number" name="notice_period_days" class="form-control" min="0" value="{{ $resignation->notice_period_days }}">
        </div>
        <div class="col-md-6">
            <label class="form-label">Accrued Leave Payout</label>
            <input type="number" name="accrued_leave_payout" class="form-control" step="0.01" min="0" value="{{ $resignation->accrued_leave_payout }}">
        </div>
        <div class="col-12">
            <label class="form-label">Reason</label>
            <textarea name="reason" class="form-control" rows="3">{{ $resignation->reason }}</textarea>
        </div>
        <div class="col-12">
            <button type="submit" class="btn btn-primary">Update</button>
        </div>
    </div>
</form>

<script>
    flatpickr('.flatpickr', { dateFormat: 'Y-m-d', allowInput: true });

    $('#resignationForm').off('submit').on('submit', function (e) {
        e.preventDefault();
        const formData = $(this).serialize();
        $.ajax({
            url: $(this).attr('action'),
            type: 'POST',
            data: formData,
            success: function () {
                $('#globalModal').modal('hide');
                if (window.resignationsTable) window.resignationsTable.draw();
                App.toast('Resignation updated successfully', 'success');
            },
            error: function (xhr) {
                const errors = xhr.responseJSON?.errors;
                if (errors) {
                    $('.is-invalid').removeClass('is-invalid');
                    $('.invalid-feedback').remove();
                    Object.keys(errors).forEach(function (field) {
                        const input = $(`[name="${field}"]`);
                        input.addClass('is-invalid');
                        input.after(`<div class="invalid-feedback">${errors[field][0]}</div>`);
                    });
                } else {
                    App.toast('Error updating resignation', 'error');
                }
            }
        });
    });
</script>
