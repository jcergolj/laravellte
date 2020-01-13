<form method="POST" wire:submit.prevent="{{ $action }}">
    @csrf

    <x-inputs.text key="name" autofocus />
    <x-inputs.text key="label" required="required" />

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
                                        class="permission-{{ $group }}"
                                        type="checkbox"
                                        wire:model="permissions.{{ $id }}.allowed"
                                        value="{{ $id }}"
                                    >
                                    {{ $permission['description'] ?? '' }}
                                </label>
                            </td>
                            <td>
                            <label class="form-check-label">
                                <input
                                    class="owner-restricted-{{ $group }}"
                                    wire:model="permissions.{{ $id }}.owner_restricted"
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
                    <x-inputs.error id="permissions.{{ $id }}.allowed" />
                    <x-inputs.error id="permissions.{{ $id }}.owner_restricted" />
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