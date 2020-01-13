<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class ConfirmedEmailController extends Controller
{
    /**
     * Save user's new email.
     *
     * @param \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     * @throws \Symfony\Component\HttpKernel\Exception\HttpException
     */
    public function store(Request $request)
    {
        auth()->logout();

        abort_if(! $request->hasValidSignature(), Response::HTTP_FORBIDDEN);

        $user = User::find($request->user);

        abort_if($user === null, Response::HTTP_FORBIDDEN);

        $user->update([
            'email' => $request->new_email,
        ]);

        msg_success('Your email has been successfully update.');

        return redirect()->route('login');
    }
}
