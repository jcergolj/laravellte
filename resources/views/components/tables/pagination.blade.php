@props(['data'])

<div class="col-sm-12 col-md-7">
    <div class="dataTables_paginate paging_simple_numbers float-right" data-turbolinks="false">
        {{ $data->appends(request()->except(['_token']))->links() }}
    </div>
</div>