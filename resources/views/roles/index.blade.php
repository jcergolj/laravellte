@section('title')
    Roles
@endsection

@section('content-header')
<x-content-header>
    Roles
</x-content-header>
@endsection

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">List of Roles</h3>
                @can('for-route', ['roles.create'])
                    <a href="{{ route('roles.create') }}" class="float-right">Add New</a>
                @endcan
            </div>

            <div class="card-body">
                <div class="dataTables_wrapper dt-bootstrap4">
                    <div class="row">
                        <x-tables.per-page />

                        <!-- div for extra filters -->
                        <div class="col-md-3 col-sm-12 form-group"></div>
                        <!-- end div for extra filters -->

                        <x-tables.search />
                    </div>

                    <x-tables.table>

                        <x-slot name="thead_tfoot">
                            <tr>
                                <th class="sorting">
                                    #
                                </th>
                                <th class="sorting">
                                    <a href="#" wire:click.prevent="sortBy('name')">Name</a>
                                    <x-tables.sort-by :sortField="$sortField" :sortDirection="$sortDirection" field="name" />
                                </th>
                                <th class="sorting">
                                    <a href="#" wire:click.prevent="sortBy('label')">Label</a>
                                    <x-tables.sort-by :sortField="$sortField" :sortDirection="$sortDirection" field="label" />
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
                            @forelse($roles as $role)
                                <tr class="@if($loop->odd) odd @endif">
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $role->name }}</td>
                                    <td>{{ $role->label }}</td>
                                    <td>{{ $role->created_at->format('d/m/Y') }}</td>
                                    <td>
                                        @can('for-route', ['roles.edit', $role])
                                            <a href="{{ route('roles.edit', $role) }}"><span class="fas fa-edit"></a></span>
                                        @endcan
                                    </td>
                                    <td>
                                        <livewire:delete-role-component :role="$role" :key="'role-'.$role->id" />
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7">No results.</td>
                                </tr>
                            @endforelse
                        </x-slot>

                    </x-tables.table>

                    <div class="row">
                        <x-tables.entries-data :data="$roles" />

                        <x-tables.pagination :data="$roles" />
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
