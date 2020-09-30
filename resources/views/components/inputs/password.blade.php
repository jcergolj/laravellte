@props(['key' => 'password', 'showHidePasswordIcon' => false])

<div class="input-group mb-3" x-data="{ showPassword: false }">
    <input
        x-ref="{{ $key }}"
        wire:model.defer="{{ $key }}"
        type="password"
        name="{{ $key }}"
        class="form-control @errorClass($key)"
        placeholder="{{ trans("validation.attributes.$key") }}"
        required
    >

    <x-inputs.fa fontAwesome="fa-lock" />

    @if ($showHidePasswordIcon)
        <div
            x-on:click="
                showPassword = !showPassword;
                if (showPassword) {
                    $refs.{{ $key }}.type = 'text';
                    $refs.icon.classList.add('fa-eye-slash');
                    $refs.icon.classList.remove('fa-eye');
                }
                else {
                    $refs.{{ $key }}.type = 'password';
                    $refs.icon.classList.add('fa-eye');
                    $refs.icon.classList.remove('fa-eye-slash');
                }
            "
            class="input-group-append"
        >
            <div class="input-group-text">
                <span x-ref="icon" class="fas fa-eye"></span>
            </div>
        </div>
    @endif

    <x-inputs.error field="{{ $key }}" />
</div>
