<?php

namespace App\Http\Controllers\AuthControllers;

use App\Http\Controllers\Controller;
use App\Mail\ResetPasswordMailer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class PasswordController extends Controller
{
    public function showForgetPassword()
    {
        return view('Auth.forgot_password');
    }

    public function sendToken(Request $request)
    {
        dd ($request->all());
    }
    public function showResetPassword($token)
    {
        $data = "We are learning Laravel 10 mail from localhost";

        Mail::to('m.ashouri.wdev@gmail.com')->send(
            new ResetPasswordMailer($data)
        );

        return view('Auth.login');
    }
}
