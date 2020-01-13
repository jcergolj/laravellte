<div class="card-body">
    <div class="dataTables_wrapper dt-bootstrap4">
        <div class="row">
            <x-tables.per-page />

            <x-tables.search />
        </div>

        <x-tables.table>

            <x-slot name="thead_tfoot">
                <tr>
                    <th class="sorting">
                        #
                    </th>
                    <th class="sorting">
                        <a href="#" wire:click.prevent="sortBy('name')" data-turbolinks="false">Name</a>
                        <x-tables.sort-by :sortField="$sortField" :sortAsc="$sortAsc" :field="'name'" />
                    </th>
                    <th class="sorting">
                        <a href="#" wire:click.prevent="sortBy('label')" data-turbolinks="false">Label</a>
                        <x-tables.sort-by :sortField="$sortField" :sortAsc="$sortAsc" :field="'label'" />
                    </th>
                    <th class="sorting">
                        <a href="#" wire:click.prevent="sortBy('created_at')" data-turbolinks="false">Created</a>
                        <x-tables.sort-by :sortField="$sortField" :sortAsc="$sortAsc" :field="'created_at'" />
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
                            <a href="{{ route('roles.edit', $role) }}"><span class="fas fa-edit"></a></span>
                        </td>
                        <td>
                            <x-inputs.delete :entity="$role" />
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

<x-modals.delete-warning />
