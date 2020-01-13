<form method="POST" wire:submit.prevent="submit">
    @csrf

    <x-inputs.password :key="'new_password'" />

    <x-inputs.password :key="'new_password_confirmation'" />

    <div class="row">
        <div class="offset-4 col-4">
            <x-inputs.button :text="'Save'" class="btn-success" />
        </div>
    </div>
</form>
