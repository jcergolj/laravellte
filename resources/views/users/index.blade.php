<div>
@section('title')
    Users
@endsection

@section('content-header')
<x-content-header>
    Users
</x-content-header>
@endsection

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">List of Users</h3>
                @can('for-route', ['users.create'])
                    <a href="{{ route('users.create') }}" class="float-right">Add New</a>
                @endcan
            </div>

            <div class="card-body" x-data="{showModal : false, deleteId : false}">
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
                        <a href="#" wire:click.prevent="sortBy('email')">Email</a>
                        <x-tables.sort-by :sortField="$sortField" :sortDirection="$sortDirection" field="email" />
                    </th>
                    <th class="sorting">
                        Role
                    </th>
                    <th class="sorting">
                        <a href="#" wire:click.prevent="sortBy('created_at')">Created</a>
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

                                        @can('for-route', ['users.edit', $user])
                                            @if(!$user->isHimself(auth()->user()))
                                                <a href="{{ route('users.edit', $user) }}"><span class="fas fa-edit"></a></span>
                                            @endif
                                        @endcan
                                    </td>
                                    <td>
                                        <livewire:delete-user-component :user="$user" :key="'user-'.$user->id" />
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

        </div>
    </div>
</div>

</div>
