@props(['data'])

<div class="col-sm-12 col-md-5">
    @if ($data->firstItem() !== null)
        <div class="dataTables_info">
            Showing {{ $data->firstItem() }} to {{ $data->lastItem() }} of {{ $data->total() }} entries
        </div>
    @endif
</div>