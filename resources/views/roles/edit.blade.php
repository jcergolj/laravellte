@section('title')
    Edit Existing Role
@endsection

@section('content-header')
    <x-content-header>
        Edit Existing Role
    </x-content-header>
@endsection

<x-savings.content>
    <x-slot name="card_header">
        <h3 class="card-title">Edit Existing Role</h3>
        <a href="{{ route('roles.index') }}" class="float-right">Back</a>
    </x-slot>

    <x-slot name="card_body">
        <form wire:submit.prevent="update" method="POST" x-data="window.permissions()">
            @csrf
            <x-inputs.text key="role.name" autofocus placeholder="{{ trans('validation.attributes.role') }}"/>
            <x-inputs.text key="role.label" required="required"
                           placeholder="{{ trans('validation.attributes.label') }}"/>

            @if (($role !== null && !$role->isAdmin()) || $role === null)
                <h3>Assign Permissions</h3>

                @foreach($permissionGroups as $group => $permissions)
                    <table class="table table-bordered" role="grid">

                        <thead>
                        <tr>
                            <th>
                                Permissions for {{ $group }}
                            </th>
                            <th>
                                Owner Restricted
                            </th>
                        </tr>

                        </thead>

                        <tbody>
                        @foreach($permissions as $id => $permission)
                            <tr>
                                <td>
                                    <label class="form-check-label">
                                        <input
                                                wire:model.lazy="permissions.{{ $id }}.allowed"
                                                class="permission-{{ $group }}"
                                                type="checkbox"
                                                @click="checkAll('select-all-permissions-{{ $group }}', 'permission-{{ $group }}')"
                                        >
                                        {{ $permission['description'] ?? '' }}
                                    </label>
                                </td>
                                <td>
                                    <label class="form-check-label">
                                        <input
                                                wire:model.defer="permissions.{{ $id }}.owner_restricted"
                                                class="owner-restricted-{{ $group }}"
                                                type="checkbox"
                                                @click="checkAll('select-all-owner-restricted-{{ $group }}', 'owner-restricted-{{ $group }}')"
                                        >
                                    </label>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>

                        <tfoot>
                        <tr>
                            <th>
                                <div class="form-check"
                                     x-data="checkAll('select-all-permissions-{{ $group }}', 'permission-{{ $group }}')">
                                    <label class="form-check-label" for="select-all-permissions-{{ $group }}"
                                           title="Select All">
                                        <input
                                                id="select-all-permissions-{{ $group }}"
                                                class="form-check-input"
                                                type="checkbox"
                                                @click="reCheckedSelectAll($event.target, 'permission-{{ $group }}')"
                                        >
                                        Select All
                                    </label>
                                </div>
                            </th>
                            <th>
                                <div class="form-check"
                                     x-data="checkAll('select-all-owner-restricted-{{ $group }}', 'owner-restricted-{{ $group }}')">
                                    <label class="form-check-label" for="select-all-owner-restricted-{{ $group }}"
                                           title="Select All">
                                        <input
                                                id="select-all-owner-restricted-{{ $group }}"
                                                class="form-check-input"
                                                type="checkbox"
                                                @click="reCheckedSelectAll($event.target, 'owner-restricted-{{ $group }}')"
                                        >
                                        Select All
                                    </label>
                                </div>
                            </th>
                        </tr>
                        </tfoot>
                    </table>

                    <div class="row">
                        @foreach($permissions as $id => $permission)
                            <x-inputs.error field="permissions.{{ $id }}.allowed"/>
                            <x-inputs.error field="permissions.{{ $id }}.owner_restricted"/>
                        @endforeach
                    </div>
                @endforeach
            @endif

            <div class="row">
                <div class="offset-8 col-4">
                    <x-inputs.button text="Save" class="btn-success"/>
                </div>
            </div>
        </form>

    </x-slot>
</x-savings.content>