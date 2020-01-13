<div class="card-body">
    <div class="dataTables_wrapper dt-bootstrap4">
        <div class="row">
            <x-tables.per-page />
            <div class="col-md-3 col-sm-12 form-group">
                <select
                    wire:model="roleId"
                    name="roleId"
                    class="form-control form-control-sm custom-select custom-select-sm"
                    value="roleId"
                    placeholder="{{ trans("validation.attributes.roleId") }}"
                >
                    <option value="">-- role --</option>
                    @foreach($roles as $role)
                        <option value="{{ $role->id }}">{{ $role->name }}</option>
                    @endforeach
                </select>
            </div>

            <x-tables.search />
        </div>

        <x-tables.table>

            <x-slot name="thead_tfoot">
                <tr>
                    <th class="sorting">
                        #
                    </th>
                    <th class="sorting">
                        <a href="#" wire:click.prevent="sortBy('email')" data-turbolinks="false">Email</a>
                        <x-tables.sort-by :sortField="$sortField" :sortDirection="$sortDirection" field="email" />
                    </th>
                    <th class="sorting">
                        Role
                    </th>
                    <th class="sorting">
                        <a href="#" wire:click.prevent="sortBy('created_at')" data-turbolinks="false">Created</a>
                        <x-tables.sort-by :sortField="$sortField" :sortDirection="$sortDirection" field="created_at" />
                    </th>
                    
                    <th class="sorting">
                        Edit
                    </th>
                    <th class="sorting">
                        Delete
                    </th>
                </tr>
            </x-slot>

            <x-slot name="tbody">
                @forelse($users as $user)
                    <tr class="@if($loop->odd) odd @endif">
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $user->email }}</td>
                        <td>{{ $user->role->label }}</td>
                        <td>{{ $user->created_at->format('d/m/Y') }}</td>
                        <td>
                            @can('for-route', 'users.edit')
                                @if(!$user->isHimself(auth()->user()))
                                    <a href="{{ route('users.edit', $user) }}"><span class="fas fa-edit"></a></span>
                                @endif
                            @endcan
                        </td>
                        <td>
                            @can('for-route', 'users.destroy')
                                @if(!$user->isHimself(auth()->user()))
                                    <a
                                        href="#"
                                        class="btn-default"
                                        @click.prevent="$refs.modal.classList.add('d-block'); deleteId={{ $user->id }};"
                                        @close.window="$refs.modal.classList.remove('d-block')"
                                    >
                                        <span class='fa fa-times'></span>
                                    </a>
                                @endif
                            @endcan
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5">No results.</td>
                    </tr>
                @endforelse
            </x-slot>

        </x-tables.table>

        <div class="row">
            <x-tables.entries-data :data="$users" />

            <x-tables.pagination :data="$users" />
        </div>
    </div>
</div>

<x-modals.delete-warning />
