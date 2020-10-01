@section('title')
    Register a new membership
@endsection

<div class="card">
    <div class="card-body register-card-body">
        <p class="login-box-msg">Register a new membership</p>

        @include('layouts._flash')

        <form wire:submit.prevent="register" method="POST">
            <x-inputs.email required="required" autofocus />

            <x-inputs.password showHidePasswordIcon="true" />

            <div class="row">
                <div class="offset-8 col-4">
                    <x-inputs.button text="Register" class="btn-primary" />
                </div>
            </div>
        </form>

        <p class="mb-1">
            <a href="{{ route('login') }}" class="text-center">I already have a membership</a>
        </p>

    </div>
</div>
