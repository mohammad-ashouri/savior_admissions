<?php

namespace App\Http\Controllers\AuthControllers;

use App\Http\Controllers\Controller;
use App\Mail\ResetPasswordMailer;
use App\Models\Auth\PasswordResetToken;
use App\Models\Catalogs\CountryPhoneCodes;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;

class PasswordController extends Controller
{
    public function showForgetPassword(): \Illuminate\Contracts\View\View|\Illuminate\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\Foundation\Application
    {
        $countryPhoneCodes = CountryPhoneCodes::where('phonecode', '!=', 0)->get();

        return view('Auth.ForgotPassword.forgot_password', compact('countryPhoneCodes'));
    }

    public function sendToken(Request $request)
    {
        $captcha = $request->input('captcha');

        // Uncomment if you want to include captcha validation
        $sessionCaptcha = session('captcha')['key'];
        if (! password_verify($captcha, $sessionCaptcha)) {
            return response()->json([
                'error' => 'Captcha is invalid.',
            ]);
        }

        $option = $request->input('reset-options');
        switch ($option) {
            case 'Mobile':
                $validator = Validator::make($request->all(), [
                    'phone_code' => [
                        'required',
                        'exists:country_phone_codes,phonecode',
                    ],
                    'mobile' => [
                        'required',
                        'numeric',
                    ],
                ]);
                if ($validator->fails()) {
                    return response()->json([
                        'errors' => $validator->errors(),
                    ]);
                }

                $prefix = CountryPhoneCodes::find($request->phone_code);

                $mobile = '+'.$request->phone_code.$request->mobile;

                $userInfo = User::whereMobile($mobile)->first();

                if (empty($userInfo)) {
                    return response()->json([
                        'errors' => [
                            'Mobile is not found!',
                        ],
                    ]);
                }

                //Make token
                $token = rand(15424, 98546);

                //Delete previous tokens
                $twoMinutesAgo = Carbon::now()->subMinutes(2);
                PasswordResetToken::whereUserId($userInfo->id)
                    ->where('created_at', '<=', $twoMinutesAgo)
                    ->delete();

                $lastToken = PasswordResetToken::whereUserId($userInfo->id)
                    ->where('created_at', '>', $twoMinutesAgo)
                    ->first();

                if (empty($lastToken)) {
                    //Make new token
                    $tokenEntry = new PasswordResetToken;
                    $tokenEntry->user_id = $userInfo->id;
                    $tokenEntry->type = 2;
                    $tokenEntry->token = $token;
                    $tokenEntry->save();

                    $messageText = "Your reset password authentication code: $token\nPlease don't share this to anyone!\nSavior Schools Support";

                    //Send token
                    $this->sendSms($mobile, $messageText);

                    return response()->json([
                        'success' => true,
                    ], 200);
                }

                $twoMinutesAgo = Carbon::now()->subMinutes(2);
                $createdTime = Carbon::parse($lastToken->created_at);

                return ['timer' => $createdTime->diffInSeconds($twoMinutesAgo)];

                break;
            case 'Email':
                $validator = Validator::make($request->all(), [
                    'email' => [
                        'required', 'email', 'exists:users,email',
                    ],
                ]);
                if ($validator->fails()) {
                    return response()->json([
                        'errors' => $validator->errors(),
                    ]);
                }
                $email = request('email');
                $userInfo = User::where('email', $email)->first();

                //Delete previous tokens
                $twoMinutesAgo = Carbon::now()->subMinutes(2);
                PasswordResetToken::whereUserId($userInfo->id)
                    ->where('created_at', '<=', $twoMinutesAgo)
                    ->delete();

                $lastToken = PasswordResetToken::whereUserId($userInfo->id)
                    ->where('created_at', '>', $twoMinutesAgo)
                    ->first();

                if (empty($lastToken)) {
                    //Send token
                    Mail::to($email)->send(
                        new ResetPasswordMailer($email)
                    );

                    return response()->json([
                        'success' => true,
                    ], 200);
                }

                $twoMinutesAgo = Carbon::now()->subMinutes(2);
                $createdTime = Carbon::parse($lastToken->created_at);

                return ['timer' => $createdTime->diffInSeconds($twoMinutesAgo)];

                break;
        }

        return response()->json([
            'errors' => 'Server Error!',
        ]);
    }

    public function authorization(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'reset-options' => [
                'required',
                'in:Mobile,Email',
            ],
            'verification_code' => [
                'required',
                'integer',
            ],
        ]);
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors(),
            ]);
        }
        $token = $request->verification_code;
        $option = $request->input('reset-options');
        switch ($option) {
            case 'Mobile':
                $validator = Validator::make($request->all(), [
                    'phone_code' => [
                        'required',
                        'exists:country_phone_codes,id',
                    ],
                    'mobile' => [
                        'required',
                        'numeric',
                    ],
                ]);
                if ($validator->fails()) {
                    return response()->json([
                        'success' => false,
                        'errors' => $validator->errors(),
                    ]);
                }
                $prefix = CountryPhoneCodes::find($request->phone_code);

                $mobile = '+'.$prefix->phonecode.$request->mobile;

                $userInfo = User::whereMobile($mobile)->first();

                if (empty($userInfo)) {
                    return response()->json([
                        'success' => false,
                        'errors' => [
                            'Mobile is not found!',
                        ],
                    ]);
                }

                //Delete previous tokens
                $deletedToken = PasswordResetToken::whereUserId($userInfo->id)->where('token', $token)->where('type', 2)->delete();

                if (! $deletedToken) {
                    return response()->json([
                        'errors' => [
                            'Failed To Reset Password!',
                        ],
                    ]);
                }
                $lastToken = new PasswordResetToken;
                $lastToken->user_id = $userInfo->id;
                $lastToken->type = 2;
                $newToken = str_replace(['/', '\\', '.'], '', bcrypt(random_bytes(20)));
                $lastToken->token = $newToken;
                $lastToken->save();

                return response()->json([
                    'success' => true,
                    'redirect_url' => [
                        env('APP_URL')."/password/reset/$newToken",
                    ],
                ]);
                break;
            case 'Email':
                $validator = Validator::make($request->all(), [
                    'email' => [
                        'required',
                        'email',
                    ],
                ]);
                if ($validator->fails()) {
                    return response()->json([
                        'success' => false,
                        'errors' => $validator->errors(),
                    ]);
                }

                $email = $request->email;

                $userInfo = User::where('email', $email)->first();

                if (empty($userInfo)) {
                    return response()->json([
                        'success' => false,
                        'errors' => [
                            'Email is not found!',
                        ],
                    ]);
                }

                //Delete previous tokens
                $deletedToken = PasswordResetToken::whereUserId($userInfo->id)->where('token', $token)->where('type', 1)->delete();

                if (! $deletedToken) {
                    return response()->json([
                        'errors' => [
                            'Failed To Reset Password!',
                        ],
                    ]);
                }
                $lastToken = new PasswordResetToken;
                $lastToken->user_id = $userInfo->id;
                $lastToken->type = 1;
                $newToken = str_replace(['/', '\\', '.'], '', bcrypt(random_bytes(20)));
                $lastToken->token = $newToken;
                $lastToken->save();

                return response()->json([
                    'success' => true,
                    'redirect_url' => [
                        env('APP_URL')."/password/reset/$newToken",
                    ],
                ]);
                break;
        }

        return response()->json([
            'success' => false,
            'errors' => 'Server Error!',
        ]);
    }

    public function showResetPassword($token)
    {
        $password_reset_tokens = PasswordResetToken::where('token', $token)->where('active', 1)->first();
        if ($password_reset_tokens) {
            return view('Auth.ForgotPassword.reset_password', compact('token'));
        }
        abort(403);
    }

    public function resetPassword(Request $request): \Illuminate\Http\JsonResponse
    {
        $resetTokenInfo = PasswordResetToken::where('token', $request->input('token'))->where('active', 1)->first();
        $validator = Validator::make($request->all(), [
            'password' => 'required|min:8|max:24|confirmed',
        ]);
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()->first()], 422);
        }
        $user = User::find($resetTokenInfo->user_id);
        $user->password = Hash::make($request->input('password'));
        if ($user->save()) {
            $resetTokenInfo->delete();

            return response()->json([
                'success' => true,
                'redirect' => route('login'),
                'flash_message' => 'Password reset successful!',
            ]);
        }

        return response()->json(['error' => 'Unknown error'], 422);
    }

    public function changePassword(Request $request)
    {
        $this->validate($request, [
            'Current_password' => 'required',
            'New_password' => 'required|min:8|max:20',
            'Confirm_password' => 'required_with:New_password|same:New_password|min:8|max:20',
        ]);
        $user = User::find(auth()->user()->id);
        if (password_verify($request->input('Current_password'), $user->password)) {
            $user->password = Hash::make($request->input('New_password'));
            $user->save();

            return response()->json(['message' => 'Password updated successfully!'], 200);
        }

        return response()->json(['message' => 'Current password is wrong!'], 422);

    }
}
