<?php

namespace App\Http\Controllers\AuthControllers;

use App\Http\Controllers\Controller;
use App\Mail\SendRegisterToken;
use App\Models\Auth\RegisterToken;
use App\Models\Catalogs\CountryPhoneCodes;
use App\Models\GeneralInformation;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class SignupController extends Controller
{
    public function index()
    {
        $countryPhoneCodes = CountryPhoneCodes::where('phonecode', '!=', 0)->get();

        return view('Auth.Signup.register', compact('countryPhoneCodes'));
    }

    public function register(Request $request)
    {
        switch (request('signup-method')) {
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
                    if ($validator->errors()->first('mobile')) {
                        $errorMessage = $validator->errors()->first('mobile', 'MobileInvalid');
                        return redirect()->back()->withErrors(['MobileInvalid' => $errorMessage])->withInput();
                    }
                    if ($validator->errors()->first('phone_code')) {
                        $errorMessage = $validator->errors()->first('phone_code', 'PhoneCodeInvalid');
                        return redirect()->back()->withErrors(['PhoneCodeInvalid' => $errorMessage])->withInput();
                    }

                }
                break;

            case 'Email':
//                $validator = Validator::make($request->all(), [
//                    'email' => [
//                        'required',
//                        'email',
//                    ],
//                ]);
//
//                if ($validator->fails()) {
//                    $errorMessage = $validator->errors()->first('email', 'EmailInvalid');
//                    return redirect()->back()->withErrors(['EmailInvalid' => $errorMessage])->withInput();
//                }
                    return redirect()->back()->withErrors(['EmailInvalid' => 'Wrong register method'])->withInput();
                break;
            default:
                abort(500);
        }

        $captcha = $request->input('captcha');

        // Uncomment if you want to include captcha validation
        $sessionCaptcha = session('captcha')['key'];
        if (! password_verify($captcha, $sessionCaptcha)) {
            return ['error' => 'Captcha is invalid.'];
        }

        switch (request('signup-method')) {
            case 'Mobile':

                $token = rand(14235, 64584);
                $prefix = CountryPhoneCodes::find($request->phone_code);
                $mobile = '+'.$prefix->phonecode.$request->mobile;

                $checkIfMobileExists = User::where('mobile', $mobile)->exists();
                if ($checkIfMobileExists) {
                    return ['error' => 'Mobile Exists. Please login!'];
                }

                //Remove previous token
                $twoMinutesAgo = Carbon::now()->subMinutes(2);
                RegisterToken::where('value', $mobile)
                    ->where('register_method', 'Mobile')
                    ->where('status', 0)
                    ->where('created_at', '<=', $twoMinutesAgo)
                    ->delete();

                $lastToken = RegisterToken::where('value', $mobile)
                    ->where('register_method', 'Mobile')
                    ->where('status', 0)
                    ->where('created_at', '>', $twoMinutesAgo)
                    ->first();

                if (empty($lastToken)) {
                    //Make new token
                    $tokenEntry = new RegisterToken();
                    $tokenEntry->register_method = 'Mobile';
                    $tokenEntry->value = $mobile;
                    $tokenEntry->token = $token;
                    $tokenEntry->status = 0;
                    $tokenEntry->save();

                    $valueToSend = 'Your registration code is: '.$token."\nDont share it to anyone!\nSavior Schools Support";

                    //Send token
                    $this->sendSms($mobile, $valueToSend);

                    $method = 'mobile';
                    $value = $mobile;

                    return ['status' => 200, 'method' => $method, 'value' => $value];
                }

                $twoMinutesAgo = Carbon::now()->subMinutes(2);
                $createdTime = Carbon::parse($lastToken->created_at);

                return ['timer' => $createdTime->diffInSeconds($twoMinutesAgo)];

                break;
            case 'Email':
                $email = $request->email;

                $checkIfEmailExists = User::where('email', $email)->exists();
                if ($checkIfEmailExists) {
                    return ['error' => 'The entered email address already exists.'];
                } else {
                    //Remove previous token
                    $twoMinutesAgo = Carbon::now()->subMinutes(2);
                    RegisterToken::where('value', $email)
                        ->where('register_method', 'Email')
                        ->where('status', 0)
                        ->where('created_at', '<=', $twoMinutesAgo)
                        ->delete();

                    $lastToken = RegisterToken::where('value', $email)
                        ->where('register_method', 'Email')
                        ->where('status', 0)
                        ->where('created_at', '>', $twoMinutesAgo)
                        ->first();

                    if (empty($lastToken)) {
                        $mailSend = Mail::to($email)->send(
                            new SendRegisterToken($email)
                        );

                        if ($mailSend) {
                            $method = 'email';
                            $value = $email;

                            return ['status' => 200, 'method' => $method, 'value' => $value];
                        } else {
                            return redirect()->back()->withErrors(['EmailSendingFailed' => 'EmailSendingFailed'])->withInput();
                        }
                    } else {
                        $twoMinutesAgo = Carbon::now()->subMinutes(2);
                        $createdTime = Carbon::parse($lastToken->created_at);

                        return ['timer' => $createdTime->diffInSeconds($twoMinutesAgo)];
                    }
                }
                break;
        }

        abort(422);
    }

    public function authorization(Request $request)
    {
        $verificationCode = $request->verification_code;
        switch (request('signup-method')) {
            case 'Mobile':
                $prefix = CountryPhoneCodes::find($request->phone_code);
                $mobile = '+'.$prefix->phonecode.$request->mobile;

                //Remove previous token
                $twoMinutesAgo = Carbon::now()->subMinutes(2);
                RegisterToken::where('value', $mobile)
                    ->where('register_method', 'Mobile')
                    ->where('status', 0)
                    ->where('created_at', '<=', $twoMinutesAgo)
                    ->delete();

                $checkAuthorizationCode = RegisterToken::where('register_method', 'Mobile')->where('value', $mobile)->where('token', $verificationCode)->first();

                if (empty($checkAuthorizationCode)) {
                    return ['error' => 'Verification code is invalid.'];
                }

                $tokenCreated = preg_replace('/[\/\\.]/', '', Str::random(32));

                $token = new RegisterToken();
                $token->register_method = 'Mobile';
                $token->value = $mobile;
                $token->token = $tokenCreated;
                $token->status = 0;
                $token->save();

                return response()->json(['redirect_url' => env('APP_URL')."/new-account/$tokenCreated"]);
                break;
            case 'Email':
                $email = $request->email;

                //Remove previous token
                $twoMinutesAgo = Carbon::now()->subMinutes(2);
                RegisterToken::where('value', $email)
                    ->where('register_method', 'Email')
                    ->where('status', 0)
                    ->where('created_at', '<=', $twoMinutesAgo)
                    ->delete();

                $checkAuthorizationCode = RegisterToken::where('register_method', 'Email')->where('value', $email)->where('token', $verificationCode)->first();

                if (empty($checkAuthorizationCode)) {
                    return ['error' => 'Verification code is invalid.'];
                }

                $tokenCreated = preg_replace('/[\/\\.]/', '', Str::random(32));

                $token = new RegisterToken();
                $token->register_method = 'Email';
                $token->value = $email;
                $token->token = $tokenCreated;
                $token->status = 0;
                $token->save();

                return response()->json(['redirect_url' => env('APP_URL')."/new-account/$tokenCreated"]);
                break;
        }

        return redirect()->route('CreateAccount.register');
    }

    public function newAccount($token)
    {
        $checkToken = RegisterToken::where('token', $token)->where('status', 0)->exists();
        if ($checkToken) {
            $tokenInfo = RegisterToken::where('token', $token)->first();

            return view('Auth.Signup.signup', ['tokenInfo' => $tokenInfo]);
        }
        return redirect()->route('login')
            ->withErrors(['WrongToken' => 'WrongToken']);
    }

    public function createAccount(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'password' => 'required|min:8|max:24',
            'repeat-password' => 'required|same:password',
            'first_name' => 'required|string',
            'last_name' => 'required|string',
            'gender' => 'required|string|in:Male,Female',
            'birthdate' => 'required|date',
            'token' => 'required|exists:register_tokens,token',
        ]);

        if ($validator->fails()) {
            return redirect()->route('login')
                ->withErrors(['errors', $validator->errors()]);
        }

        $registerToken = RegisterToken::where('token', $request->token)->first();
        $gender = $request->gender;

        $user = new User();
        if ($registerToken->register_method == 'Email') {
            $user->email = $registerToken->value;
        } elseif ($registerToken->register_method == 'Mobile') {
            $user->mobile = $registerToken->value;
        }
        $user->password = $request->password;

        $user->save();

        $user->assignRole('Parent');

        $generalInformations = new GeneralInformation();
        $generalInformations->user_id = $user->id;
        $generalInformations->first_name_en = $request->first_name;
        $generalInformations->last_name_en = $request->last_name;
        $generalInformations->gender = $gender;
        $generalInformations->birthdate = $request->birthdate;
        $generalInformations->save();

        RegisterToken::where('token', $request->token)->delete();

        Session::put('id', $user->id);
        Auth::loginUsingId(session('id'));

        return redirect()->route('dashboard');
    }
}
