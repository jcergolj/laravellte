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
        <form wire:submit.prevent="update" method="POST">
            @csrf

            <x-inputs.text key="role.name" autofocus placeholder="{{ trans('validation.attributes.role') }}" />
            <x-inputs.text key="role.label" required="required" placeholder="{{ trans('validation.attributes.label') }}" />

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
                                                wire:model="permissions.{{ $id }}.allowed"
                                                class="permission-{{ $group }}"
                                                type="checkbox"
                                                value="1"
                                            >
                                            {{ $permission['description'] ?? '' }}
                                        </label>
                                    </td>
                                    <td>
                                    <label class="form-check-label">
                                        <input
                                            wire:model="permissions.{{ $id }}.owner_restricted"
                                            class="owner-restricted-{{ $group }}"
                                            type="checkbox"
                                            value="1"
                                        >
                                    </label>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>

                        <tfoot>
                            <tr>
                                <th>
                                    <div class="form-check">
                                        <label class="form-check-label" for="select-all-permissions-{{ $group }}" title="Select All">
                                            <input
                                                id="select-all-permissions-{{ $group }}"
                                                class="form-check-input"
                                                type="checkbox"
                                                value="1"
                                                @click="
                                                    for(i in document.getElementsByClassName('permission-{{ $group }}')) {
                                                        document.getElementsByClassName('permission-{{ $group }}')[i].checked = $event.target.checked
                                                    }
                                                "
                                            >
                                            Select All
                                        </label>
                                    </div>
                                </th>
                                <th>
                                    <div class="form-check">
                                        <label class="form-check-label" for="select-all-owner-restricted-{{ $group }}" title="Select All">
                                            <input
                                                id="select-all-owner-restricted-{{ $group }}"
                                                class="form-check-input"
                                                type="checkbox"
                                                value="1"
                                                @click="
                                                    for(i in document.getElementsByClassName('owner-restricted-{{ $group }}')) {
                                                        document.getElementsByClassName('owner-restricted-{{ $group }}')[i].checked = $event.target.checked
                                                    }
                                                "
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
                            <x-inputs.error field="permissions.{{ $id }}.allowed" />
                            <x-inputs.error field="permissions.{{ $id }}.owner_restricted" />
                        @endforeach
                    </div>
                @endforeach
            @endif

            <div class="row">
                <div class="offset-8 col-4">
                    <x-inputs.button text="Save" class="btn-success" />
                </div>
            </div>
        </form>

    </x-slot>
</x-savings.content>
