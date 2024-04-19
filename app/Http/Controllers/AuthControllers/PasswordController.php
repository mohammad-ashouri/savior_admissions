<?php

namespace App\Http\Controllers\AuthControllers;

use App\Http\Controllers\Controller;
use App\Mail\ResetPasswordMailer;
use App\Models\Auth\PasswordResetToken;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;

class PasswordController extends Controller
{
    public function showForgetPassword(): \Illuminate\Contracts\View\View|\Illuminate\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\Foundation\Application
    {
        return view('Auth.forgot_password');
    }

    public function sendToken(Request $request)
    {
        $option = $request->input('reset-options');
        switch ($option) {
            case 'Mobile':
                $mobile = $request->input('mobile');
                if (! $mobile) {
                    $this->logActivity(json_encode(['activity' => 'Password Reset Failed (Null Mobile)']), request()->ip(), request()->userAgent());

                    return response()->json([
                        'success' => false,
                        'errors' => [
                            'Mobile' => ['Mobile is required'],
                        ],
                    ]);
                }
                $validator = Validator::make($request->all(), [
                    'mobile' => [
                        'required',
                        function ($attribute, $value, $fail) {
                            if (! preg_match('/^\+989\d{9}$/', $value)) {
                                $fail('The '.$attribute.' is not in the correct format.');
                            }
                        },
                    ],
                ]);
                if ($validator->fails()) {
                    return response()->json([
                        'success' => false,
                        'errors' => [
                            'WrongMobile' => ['Mobile is entered in the wrong format'],
                        ],
                    ]);
                }
                $token = str_replace(['/', '\\', '.'], '', bcrypt(random_bytes(10)));
                $userInfo = User::where('mobile', $mobile)->first();

                //Delete previous tokens
                PasswordResetToken::where('user_id', $userInfo->id)->delete();

                //Make new token
                $tokenEntry = new PasswordResetToken();
                $tokenEntry->user_id = $userInfo->id;
                $tokenEntry->type = 2;
                $tokenEntry->token = $token;
                $tokenEntry->save();

                $valueToSend = 'Your reset password link is: '.env('APP_URL').'/password/reset/'.$token."\nPlease don't share this to anyone!\nSavior Schools Support";

                //Send token
                $this->sendSms($mobile, $valueToSend);

                return response()->json([
                    'success' => true,
                    'redirect' => route('login'),
                ]);
                break;
            case 'Email':
                $email = $request->input('email');
                if (! $email) {
                    $this->logActivity(json_encode(['activity' => 'Password Reset Failed (Null Email)']), request()->ip(), request()->userAgent());

                    return response()->json([
                        'success' => false,
                        'errors' => [
                            'Email' => ['Email is required'],
                        ],
                    ]);
                }

                $user = User::where('email', $email)->first();
                if (! $user) {
                    $this->logActivity(json_encode(['activity' => 'Password Reset Failed (Wrong Email)', 'email' => $email]), request()->ip(), request()->userAgent());

                    return response()->json([
                        'success' => false,
                        'errors' => [
                            'WrongEmail' => ['Email is not found'],
                        ],
                    ]);
                }

                Mail::to($email)->send(
                    new ResetPasswordMailer($email)
                );

                return response()->json([
                    'success' => true,
                    'redirect' => route('login'),
                ]);
                break;
        }
    }

    public function resetPassword(Request $request): \Illuminate\Http\JsonResponse
    {
        $resetTokenInfo = PasswordResetToken::where('token', $request->input('token'))->where('active', 1)->first();
        $validator = Validator::make($request->all(), [
            'password' => 'required|min:8|max:24|confirmed',
        ]);
        if ($validator->fails()) {
            $this->logActivity(json_encode(['activity' => 'Failed Resetting Password', 'errors' => $validator->errors()->first()]), request()->ip(), request()->userAgent(), $resetTokenInfo->user_id);

            return response()->json(['error' => $validator->errors()->first()], 422);
        }
        $user = User::find($resetTokenInfo->user_id);
        $user->password = Hash::make($request->input('password'));
        if ($user->save()) {
            $resetTokenInfo->active = 0;
            $resetTokenInfo->save();
            $this->logActivity(json_encode(['activity' => 'Password Reset Successfully', 'email' => $request->input('email')]), request()->ip(), request()->userAgent(), $resetTokenInfo->user_id);

            return response()->json([
                'success' => true,
                'redirect' => route('login'),
                'flash_message' => 'Password reset successful!',
            ]);
        }
        $this->logActivity(json_encode(['activity' => 'Failed Resetting Password', 'errors' => $validator->errors()->first()]), request()->ip(), request()->userAgent(), $resetTokenInfo->user_id);

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

    public function changePassword(Request $request)
    {
        $this->validate($request, [
            'Current_password' => 'required',
            'New_password' => 'required|min:8|max:20',
            'Confirm_password' => 'required_with:New_password|same:New_password|min:8|max:20',
        ]);
        $user = User::find(session('id'));
        if (password_verify($request->input('Current_password'), $user->password)) {
            $user->password = Hash::make($request->input('New_password'));
            $user->save();

            return redirect()->back()->withSuccess('Password updated successfully!');
        }
    }
}
