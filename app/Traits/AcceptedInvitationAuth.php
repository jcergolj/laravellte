<?php

namespace App\Traits;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

trait AcceptedInvitationAuth
{
    /**
     * Authorize accepted invitation request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\User  $user
     * @return void
     * @throws \Symfony\Component\HttpKernel\Exception\HttpException
     */
    public function authorizeInvitation(Request $request, User $user = null)
    {
        if (! $request->hasValidSignature()) {
            abort(Response::HTTP_FORBIDDEN, 'The link does not have a valid signature or is expired.');
        }

        if ($user === null) {
            abort(Response::HTTP_FORBIDDEN, 'User was not found.Could not find a user.');
        }

        if ($user->password !== null) {
            return abort(Response::HTTP_FORBIDDEN, 'The link has already been used.');
        }
    }
}
