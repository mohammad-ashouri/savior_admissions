<?php

namespace App\Http\Controllers\GeneralControllers;

use App\Http\Controllers\Controller;
use App\Models\User;

class ProfileController extends Controller
{
    public function index()
    {
        $myInfo = User::find(session('id'));
        return view('GeneralPages.profile',compact('myInfo'));
    }
}
