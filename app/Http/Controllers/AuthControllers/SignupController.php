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
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class SignupController extends Controller
{
    public function index()
    {
        $this->logActivity(json_encode(['activity' => 'Getting Signup Authorization Index Page']), request()->ip(), request()->userAgent());

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
                        $this->logActivity(json_encode(['activity' => 'Wrong Entered Values For Signup', 'errors' => json_encode($validator->errors()), 'values' => $request->all()]), request()->ip(), request()->userAgent());

                        return redirect()->back()->withErrors(['MobileInvalid' => $errorMessage])->withInput();
                    }
                    if ($validator->errors()->first('phone_code')) {
                        $errorMessage = $validator->errors()->first('phone_code', 'PhoneCodeInvalid');
                        $this->logActivity(json_encode(['activity' => 'Wrong Entered Values For Signup', 'errors' => json_encode($validator->errors()), 'values' => $request->all()]), request()->ip(), request()->userAgent());

                        return redirect()->back()->withErrors(['PhoneCodeInvalid' => $errorMessage])->withInput();
                    }

                }
                break;

            case 'Email':
                $validator = Validator::make($request->all(), [
                    'email' => [
                        'required',
                        'email',
                    ],
                ]);

                if ($validator->fails()) {
                    $errorMessage = $validator->errors()->first('email', 'EmailInvalid');
                    $this->logActivity(json_encode(['activity' => 'Wrong Entered Values For Signup', 'errors' => json_encode($validator->errors()), 'values' => $request->all()]), request()->ip(), request()->userAgent());

                    return redirect()->back()->withErrors(['EmailInvalid' => $errorMessage])->withInput();
                }
                break;
            default:
                abort(500);
        }

        $captcha = $request->input('captcha');

        // Uncomment if you want to include captcha validation
        $sessionCaptcha = session('captcha')['key'];
        if (! password_verify($captcha, $sessionCaptcha)) {
            $this->logActivity(json_encode(['activity' => 'Register Failed (Wrong Captcha)', 'entered_values' => json_encode($request->all())]), request()->ip(), request()->userAgent());

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
                    //                    $this->sendSms($mobile, $valueToSend);

                    $this->logActivity(json_encode(['activity' => 'SMS Token Sent', 'mobile' => $mobile, 'values' => json_encode([$tokenEntry, $valueToSend])]), request()->ip(), request()->userAgent());
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
                    $this->logActivity(json_encode(['activity' => 'Email Token Sending Failed', 'errors' => $errorMessage]), request()->ip(), request()->userAgent());

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
                            $this->logActivity(json_encode(['activity' => 'Email Token Sent', 'email' => $email]), request()->ip(), request()->userAgent());
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
        $this->logActivity(json_encode(['activity' => 'Authorization Aborted']), request()->ip(), request()->userAgent());

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
                    $this->logActivity(json_encode(['activity' => 'Authorization Aborted', 'errors' => 'Verification Code Is Wrong']), request()->ip(), request()->userAgent());

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

                $checkIfEmailExists = User::where('email', $email)->exists();
                if ($checkIfEmailExists) {
                    $errorMessage = 'The entered email address already exists.';
                    $this->logActivity(json_encode(['activity' => 'Email Token Sending Failed', 'errors' => $errorMessage]), request()->ip(), request()->userAgent());

                    return redirect()->route('login')->withErrors(['errors' => $errorMessage]);

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
                        $twoMinutesAgo = Carbon::now()->subMinutes(2);
                        $createdTime = Carbon::parse($lastToken->created_at);

                        if ($mailSend) {
                            $this->logActivity(json_encode(['activity' => 'Email Token Sent', 'email' => $email]), request()->ip(), request()->userAgent());

                            return ['timer' => $createdTime->diffInSeconds($twoMinutesAgo)];
                        } else {
                            $twoMinutesAgo = Carbon::now()->subMinutes(2);
                            $createdTime = Carbon::parse($lastToken->created_at);

                            return ['timer' => $createdTime->diffInSeconds($twoMinutesAgo)];
                        }
                    } else {
                        $this->logActivity(json_encode(['activity' => 'Email Token Sending Failed', 'email' => $email]), request()->ip(), request()->userAgent());

                        return redirect()->route('login')->with(['EmailSendingFailed' => 'EmailSendingFailed']);
                    }
                }
        }

        return redirect()->route('CreateAccount.register');
    }

    public function newAccount($token)
    {
        $this->logActivity(json_encode(['activity' => 'Checking New Account Token']), request()->ip(), request()->userAgent());

        $checkToken = RegisterToken::where('token', $token)->where('status', 0)->exists();
        if ($checkToken) {
            $tokenInfo = RegisterToken::where('token', $token)->first();
            $this->logActivity(json_encode(['activity' => 'Getting New Account Page']), request()->ip(), request()->userAgent());

            return view('Auth.Signup.signup', ['tokenInfo' => $tokenInfo]);
        }
        $this->logActivity(json_encode(['activity' => 'Getting New Account Page Failed', 'errors' => 'Token Is Wrong']), request()->ip(), request()->userAgent());

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
            $this->logActivity(json_encode(['activity' => 'Wrong Entered Values For Signup', 'errors' => json_encode($validator->errors()), 'values' => $request->all()]), request()->ip(), request()->userAgent());

            return redirect()->route('login')
                ->withErrors(['errors', $validator->errors()]);
        }

        $registerToken = RegisterToken::where('token', $request->token)->first();

        $user = new User();
        if ($registerToken->register_method == 'Email') {
            $user->email = $registerToken->value;
        } elseif ($registerToken->register_method == 'Mobile') {
            $user->mobile = $registerToken->value;
        }
        $user->password = $request->password;

        $user->save();

        $gender = $request->gender;

        $generalInformations = new GeneralInformation();
        $generalInformations->user_id = $user->id;
        $generalInformations->first_name_en = $request->first_name;
        $generalInformations->last_name_en = $request->last_name;
        $generalInformations->gender = $gender;
        $generalInformations->birthdate = $request->birthdate;
        $generalInformations->save();

        switch ($gender) {
            case 'Male':
                $user->assignRole('Parent(Father)');
                break;
            case 'Female':
                $user->assignRole('Parent(Mother)');
                break;
        }
        $this->logActivity(json_encode(['activity' => 'User Registered Successfully', 'user_id' => $user->id]), request()->ip(), request()->userAgent());

        $this->logActivity(json_encode(['activity' => 'Token Deleted', 'token_id' => $registerToken->id]), request()->ip(), request()->userAgent());

        RegisterToken::where('token', $request->token)->delete();

        Session::put('id', $user->id);
        $this->logActivity(json_encode(['activity' => 'Login Succeeded', 'user_id' => $user->id]), request()->ip(), request()->userAgent());

        return redirect()->route('dashboard');
    }
}
