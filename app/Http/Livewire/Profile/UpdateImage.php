<?php

namespace App\Http\Livewire\Profile;

use App\Events\ProfileImageUploaded;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\WithFileUploads;

class UpdateImage extends Component
{
    use WithFileUploads;

    /** @var \App\Models\User */
    public $user;

    /** @var \Livewire\TemporaryUploadedFile */
    public $image;

    /**
     * Throws auth exception if user is not authenticated.
     *
     * @return void
     * @throws \Illuminate\Auth\AuthenticationException
     */
    public function hydrate()
    {
        if (auth()->guest()) {
            throw new AuthenticationException();
        }
    }

    /**
     * Component mount.
     *
     * @return void
     */
    public function mount()
    {
        $this->user = auth()->user();
    }

    /**
     * Submit the form.
     *
     * @return void
     */
    public function submit()
    {
        $this->validate($this->validationRules());

        if ($this->user->image !== null) {
            Storage::disk('avatar')->delete("{$this->user->image}");
        }

        $imageName = $this->image->store('/', 'avatar');

        $this->user->saveImage($imageName);

        ProfileImageUploaded::dispatch($this->user);

        msg_success('Your profile\'s image has been successfully updated');

        return redirect()->route('profile.users.index');
    }

    /**
     * Render the component view.
     *
     * @return \Illuminate\View\View
     */
    public function render()
    {
        return view('livewire.profile.update-image');
    }

    /**
     * Get the validation rules.
     *
     * @return array
     */
    private function validationRules()
    {
        return [
            'image' => [
                'required',
                'image',
                'dimensions:min_width=100,min_height=100',
            ],
        ];
    }
}
