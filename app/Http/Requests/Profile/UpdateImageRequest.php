<?php

namespace App\Http\Requests\Profile;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Storage;

class UpdateImageRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'image' => [
                'required',
                'image',
                'dimensions:min_width=100,min_height=100',
            ],
        ];
    }

    /**
     * Upload and save user's image file.
     *
     * @param \App\Models\User  $user
     * @return mixed
     */
    public function persist($user)
    {
        if ($user->image !== null) {
            Storage::disk('avatar')->delete("{$user->image}");
        }

        $imageName = $user->saveImage();

        Storage::disk('avatar')
            ->putFileAs(
                '',
                $this->file('image'),
                $imageName
            );
    }
}
