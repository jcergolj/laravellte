<form method="POST" wire:submit.prevent="{{ $action }}">
    @csrf

    <x-inputs.email required="required" autofocus />

    <x-inputs.dropdown key="roleId" :options="$roles" textField="name"  />

    <div class="row">
        <div class="offset-8 col-4">
            <x-inputs.button text="Save" class="btn-success" />
        </div>
    </div>
</form>