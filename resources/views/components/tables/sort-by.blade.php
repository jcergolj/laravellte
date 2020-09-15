@props(['sortField', 'field', 'sortDirection'])

@if ($sortField !== $field)
    <i class="text-muted fas fa-sort"></i>
@elseif ($sortDirection === 'asc')
    <i class="fas fa-sort-up"></i>
@else
    <i class="fas fa-sort-down"></i>
@endif
