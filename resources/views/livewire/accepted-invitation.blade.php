<form method="POST" wire:submit.prevent="submit">
    @csrf

    <x-inputs.password key="newPassword" />

    <x-inputs.password key="newPasswordConfirmation" />

    <div class="row">
        <div class="offset-4 col-4">
            <x-inputs.button text="Save" class="btn-success" />
        </div>
    </div>
</form>
