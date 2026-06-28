<form action="{{ route('roles.update', $role->id) }}" method="POST" id="permissionsForm">
    @csrf
    @method('PUT')
    <input type="hidden" name="name" value="{{ $role->name }}">
    <div class="row">
        @foreach($permissions as $group => $perms)
            <div class="col-md-6 mb-3">
                <h6 class="fw-bold border-bottom pb-1">{{ ucfirst($group) }}</h6>
                @foreach($perms as $permission)
                    <div class="form-check">
                        <input type="checkbox" name="permissions[]" value="{{ $permission->id }}"
                               class="form-check-input" id="perm_{{ $permission->id }}"
                               {{ in_array($permission->id, $rolePermissions) ? 'checked' : '' }}>
                        <label class="form-check-label" for="perm_{{ $permission->id }}">
                            {{ $permission->name }}
                        </label>
                    </div>
                @endforeach
            </div>
        @endforeach
    </div>
    <div class="text-end">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
        <button type="submit" class="btn btn-primary">Save Permissions</button>
    </div>
</form>
<script>
    App.form('#permissionsForm', {
        success: function () {
            $('#globalModal').modal('hide');
        }
    });
</script>
