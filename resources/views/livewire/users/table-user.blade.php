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
                        <a href="#" wire:click.prevent="sortBy('email')" data-turbolinks="false">Email</a>
                        <x-tables.sort-by :sortField="$sortField" :sortAsc="$sortAsc" :field="'email'" />
                    </th>
                    <th class="sorting">
                        <a href="#">Role</a>
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
                @forelse($users as $user)
                    <tr class="@if($loop->odd) odd @endif">
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $user->email }}</td>
                        <td>{{ $user->role->label }}</td>
                        <td>{{ $user->created_at->format('d/m/Y') }}</td>
                        <td>
                            @if(!$user->isHimself(auth()->user()))
                                <a href="{{ route('users.edit', $user) }}"><span class="fas fa-edit"></a></span>
                            @endif
                        </td>
                        <td>
                            @if(!$user->isHimself(auth()->user()))
                                <a
                                    href="#"
                                    class="btn-default"
                                    data-entity-id="{{ $user->id }}"
                                    @click.prevent="$refs.modal.classList.add('d-block'); deleteId=event.target.parentNode.getAttribute('data-entity-id'); "
                                    @close.window="$refs.modal.classList.remove('d-block')"
                                >
                                    <span class='fa fa-times'></span>
                                </a>
                            @endif
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
