<div class="d-inline-flex dropdown ms-2">
    <a href="javascript:void(0)" class="d-inline-flex align-items-center text-body dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
        <i class="ph ph-list"></i>
    </a>
    <div class="dropdown-menu dropdown-menu-end" style="">
        @can('users-management.users.edit')
            <a href="{{ route('users-management.users.edit', $user->id) }}" class="dropdown-item">
                <i class="ph ph-pencil me-2"></i>
                {{ __('global.edit') }}
            </a>
        @endcan
        @can('users-management.users.change-password')
            <a href="{{ route('users-management.users.change-password', $user->id) }}" class="dropdown-item" onclick="changePassword(event)">
                <i class="ph ph-password me-2"></i>
                {{ __('global.change_password') }}
            </a>
        @endcan
        @can('users-management.users.permission')
            <a href="{{ route('users-management.users.permission', $user->id) }}" class="dropdown-item" onclick="permission(event)">
                <i class="ph ph-person-simple-circle me-2"></i>
                {{ __('global.permission') }}
            </a>
        @endcan
        @can('users-management.users.delete')
            <div class="dropdown-divider"></div>
            <a href="{{ route('users-management.users.delete', $user->id) }}" class="dropdown-item" onclick="deleteRecord(event)">
                <i class="ph ph-trash text-danger me-2"></i>
                {{ __('global.delete') }}
            </a>
        @endcan
    </div>
</div>
