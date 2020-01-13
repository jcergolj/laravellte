<?php

namespace App\Http\Controllers\Profile;

use App\Events\ProfileImageUploaded;
use App\Http\Controllers\Controller;
use App\Http\Requests\Profile\UpdateImageRequest;
use Illuminate\Http\Response;

class ImageController extends Controller
{
    /**
     * Update auth user's image.
     *
     * @param  \App\Http\Requests\Profile\UpdateImageRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateImageRequest $request)
    {
        $request->persist(auth()->user());

        msg_success('Your profile\'s image has been successfully updated');

        ProfileImageUploaded::dispatch((auth()->user()));

        return response()->json([], Response::HTTP_NO_CONTENT);
    }
}
