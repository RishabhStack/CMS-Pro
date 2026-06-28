<form action="{{ isset($review) ? route('performance-reviews.update', $review->id) : route('performance-reviews.store') }}" method="POST" id="reviewForm">
    @csrf
    @if(isset($review))
        @method('PUT')
    @endif
    <div class="row g-3">
        <div class="col-md-6">
            <label class="form-label">Employee <span class="text-danger">*</span></label>
            <select name="employee_id" class="form-select" required>
                <option value="">Select Employee</option>
                @foreach($employees as $emp)
                    <option value="{{ $emp->id }}" {{ (old('employee_id', $review->employee_id ?? '') == $emp->id) ? 'selected' : '' }}>
                        {{ $emp->full_name }}
                    </option>
                @endforeach
            </select>
        </div>
        <div class="col-md-6">
            <label class="form-label">Reviewer</label>
            <select name="reviewer_id" class="form-select">
                <option value="">Select Reviewer</option>
                @foreach($employees as $emp)
                    <option value="{{ $emp->user_id }}" {{ (old('reviewer_id', $review->reviewer_id ?? '') == $emp->user_id) ? 'selected' : '' }}>
                        {{ $emp->full_name }}
                    </option>
                @endforeach
            </select>
        </div>
        <div class="col-md-6">
            <label class="form-label">Review Period <span class="text-danger">*</span></label>
            <input type="text" name="review_period" class="form-control" value="{{ old('review_period', $review->review_period ?? '') }}" placeholder="e.g. Q1 2026" required>
        </div>
        <div class="col-md-6">
            <label class="form-label">Due Date</label>
            <input type="date" name="due_date" class="form-control flatpickr" value="{{ old('due_date', isset($review) && $review->due_date ? $review->due_date->format('Y-m-d') : '') }}">
        </div>
        <div class="col-md-6">
            <label class="form-label">Start Date <span class="text-danger">*</span></label>
            <input type="date" name="start_date" class="form-control flatpickr" value="{{ old('start_date', isset($review) && $review->start_date ? $review->start_date->format('Y-m-d') : '') }}" required>
        </div>
        <div class="col-md-6">
            <label class="form-label">End Date <span class="text-danger">*</span></label>
            <input type="date" name="end_date" class="form-control flatpickr" value="{{ old('end_date', isset($review) && $review->end_date ? $review->end_date->format('Y-m-d') : '') }}" required>
        </div>
        <div class="col-12">
            <label class="form-label">Employee Notes</label>
            <textarea name="employee_notes" class="form-control" rows="3">{{ old('employee_notes', $review->employee_notes ?? '') }}</textarea>
        </div>
        @if(isset($review))
            <div class="col-12">
                <label class="form-label">Reviewer Notes</label>
                <textarea name="reviewer_notes" class="form-control" rows="3">{{ old('reviewer_notes', $review->reviewer_notes ?? '') }}</textarea>
            </div>
        @endif
    </div>
    <div class="text-end mt-3">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
        <button type="submit" class="btn btn-primary">{{ isset($review) ? 'Update' : 'Create' }}</button>
    </div>
</form>
<script>
    App.form('#reviewForm', {
        success: function () {
            $('#globalModal').modal('hide');
            if (window.reviewsTable) window.reviewsTable.draw();
        }
    });
</script>
