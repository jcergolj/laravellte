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

            <div class="row">
                <div class="offset-8 col-4">
                    <x-inputs.button text="Save" class="btn-success" />
                </div>
            </div>
        </form>

    </x-slot>
</x-savings.content>
