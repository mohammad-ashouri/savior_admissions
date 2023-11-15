<?php

namespace App\Http\Controllers\AuthControllers;

use App\Http\Controllers\Controller;
use App\Mail\ResetPasswordMailer;
use App\Models\Auth\PasswordResetToken;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;

class PasswordController extends Controller
{
    public function showForgetPassword()
    {
        return view('Auth.forgot_password');
    }

    public function sendToken(Request $request)
    {
        $option = $request->input('reset-options');
        switch ($option) {
            case 'Mobile':
                $mobile = $request->input('mobile');
                if (!$mobile) {
                    $this->logActivity('Reset Failed (Null Mobile)', request()->ip(), request()->userAgent());
                    return response()->json([
                        'success' => false,
                        'errors' => [
                            'Mobile' => ['Mobile is required']
                        ]
                    ]);
                }
                $validator = Validator::make($request->all(), [
                    'mobile' => [
                        'required',
                        function ($attribute, $value, $fail) {
                            if (!preg_match('/^\+989\d{9}$/', $value)) {
                                $fail('The ' . $attribute . ' is not in the correct format.');
                            }
                        },
                    ],
                ]);
                if ($validator->fails()) {
                    return response()->json([
                        'success' => false,
                        'errors' => [
                            'WrongMobile' => ['Mobile is entered in the wrong format']
                        ]
                    ]);
                }
                break;
            case 'Email':
                $email = $request->input('email');
                if (!$email) {
                    $this->logActivity('Reset Failed (Null Email)', request()->ip(), request()->userAgent());
                    return response()->json([
                        'success' => false,
                        'errors' => [
                            'Email' => ['Email is required']
                        ]
                    ]);
                }

                $user = User::where('email', $email)->first();
                if (!$user) {
                    $this->logActivity('Reset Failed (Wrong Email) - Entered email => ' . $email, request()->ip(), request()->userAgent());
                    return response()->json([
                        'success' => false,
                        'errors' => [
                            'WrongEmail' => ['Email is not found']
                        ]
                    ]);
                }

                Mail::to('m.ashouri.wdev@gmail.com')->send(
                    new ResetPasswordMailer($email)
                );

                return response()->json([
                    'success' => true,
                    'redirect' => route('login')
                ]);
                break;
        }
    }

    public function resetPassword(Request $request)
    {
        $resetTokenInfo = PasswordResetToken::where('token', $request->input('token'))->where('active', 1)->first();
        $validator = Validator::make($request->all(), [
            'password' => 'required|min:8|max:24|confirmed',
        ]);
        if ($validator->fails()) {
            $this->logActivity('Failed Resetting Password=> ' . $validator->errors()->first(), request()->ip(), request()->userAgent(), $resetTokenInfo->user_id);
            return response()->json(['error' => $validator->errors()->first()], 422);
        }
        $user = User::find($resetTokenInfo->user_id);
        $user->password = Hash::make($request->input('password'));
        if ($user->save()) {
            $resetTokenInfo->active = 0;
            $resetTokenInfo->save();
            $this->logActivity('Password Resetted Successfully => ' . $request->input('email'), request()->ip(), request()->userAgent(), $resetTokenInfo->user_id);
            return response()->json([
                'success' => true,
                'redirect' => route('login'),
                'flash_message' => 'Password reset successful!',
            ]);
        }
        $this->logActivity('Failed Resetting Password=> ' . $validator->errors()->first(), request()->ip(), request()->userAgent(), $resetTokenInfo->user_id);
        return response()->json(['error' => 'Unknown error'], 422);
    }

    public function showResetPassword($token)
    {
        $password_reset_tokens = PasswordResetToken::where('token', $token)->where('active', 1)->first();
        if ($password_reset_tokens) {
            return view('Auth.reset_password', compact('token'));
        }
        abort(403);
    }
}
