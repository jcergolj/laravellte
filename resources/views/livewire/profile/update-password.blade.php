<form method="POST" wire:submit.prevent="submit" x-show="show" x-transition:enter="fade">
    @csrf

    <x-inputs.password key="newPassword" autofocus />

    <x-inputs.password key="newPasswordConfirmation" />

    <x-inputs.password key="currentPassword" />

    <div class="row">
        <div class="offset-4 col-4">
            <x-inputs.button text="Save" class="btn-success" />
        </div>

        <div class="col-4">
            <button type="button" class="btn btn-outline-secondary btn-block" x-on:click="show = false">Cancel</button>
        </div>
    </div>
</form>
