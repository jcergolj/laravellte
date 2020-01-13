<form method="POST" wire:submit.prevent="{{ $action }}">
    @csrf

    <x-inputs.text :key="'name'" required="required" />
    <x-inputs.text :key="'label'" required="required" />

    <div class="row">
        <div class="offset-8 col-4">
            <x-inputs.button :text="'Save'" class="btn-success" />
        </div>
    </div>
</form>