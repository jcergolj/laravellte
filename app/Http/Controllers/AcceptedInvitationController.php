<?php

namespace App\Http\Controllers;

use App\Http\AcceptedInvitationAuth;
use App\Models\User;
use Illuminate\Http\Request;

class AcceptedInvitationController extends Controller
{
    use AcceptedInvitationAuth;

    /**
     * Show the form for creating a new resource.
     *
     * @param \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $user = User::find($request->user);
        $this->authorizeInvitation($request, $user);

        return view('accepted-invitations.create', ['user' => $user]);
    }
}
