<?php

namespace App\Http\Controllers\Profile;

use App\Http\Controllers\Controller;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('profile.users.index', ['user' => auth()->user()]);
    }
}
