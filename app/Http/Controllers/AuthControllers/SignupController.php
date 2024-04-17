<?php

namespace App\Http\Controllers\AuthControllers;

use App\Http\Controllers\Controller;
use App\Mail\SendRegisterToken;
use App\Models\Auth\RegisterToken;
use App\Models\Catalogs\CountryPhoneCodes;
use App\Models\GeneralInformation;
use App\Models\User;
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

        return view('Auth.Signup.authorization', compact('countryPhoneCodes'));
    }

    public function authorization(Request $request)
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

        switch (request('signup-method')) {
            case 'Mobile':
                $token = preg_replace('/[\/\\.]/', '', Str::random(32));
                $prefix = CountryPhoneCodes::find($request->phone_code);
                $mobile = '+'.$prefix->phonecode.$request->mobile;

                $checkIfMobileExists=User::where('mobile',$mobile)->exists();
                if ($checkIfMobileExists) {
                    return redirect()->back()->withErrors(['MobileExists' => 'MobileExists'])->withInput();
                }

                //Remove previous token
                RegisterToken::where('value', $mobile)->where('register_method', 'Mobile')->where('status', 0)->delete();

                //Make new token
                $tokenEntry = new RegisterToken();
                $tokenEntry->register_method = 'Mobile';
                $tokenEntry->value = $mobile;
                $tokenEntry->token = $token;
                $tokenEntry->status = 0;
                $tokenEntry->save();

                $valueToSend = 'Your registration link is: '.env('APP_URL').'/new-account/'.$token."\nYou have one hour to register.\nSavior Schools Support";

                //Send token
                $this->sendSms($mobile, $valueToSend);
                $this->logActivity(json_encode(['activity' => 'SMS Token Sent', 'mobile' => $mobile, 'values' => json_encode([$tokenEntry, $valueToSend])]), request()->ip(), request()->userAgent());

                return redirect()->route('login')->with(['SMSSent' => 'SMSSent']);
            case 'Email':
                $email = $request->email;

                $checkIfEmailExists = User::where('email', $email)->exists();
                if ($checkIfEmailExists) {
                    $errorMessage = 'The entered email address already exists.';
                    $this->logActivity(json_encode(['activity' => 'Email Token Sending Failed', 'errors' => $errorMessage]), request()->ip(), request()->userAgent());

                    return redirect()->route('login')->withErrors(['errors' => $errorMessage]);

                } else {
                    $mailSend = Mail::to($email)->send(
                        new SendRegisterToken($email)
                    );

                    if ($mailSend) {
                        $this->logActivity(json_encode(['activity' => 'Email Token Sent', 'email' => $email]), request()->ip(), request()->userAgent());

                        return redirect()->route('login')->with(['EmailSent' => 'EmailSent']);
                    } else {
                        $this->logActivity(json_encode(['activity' => 'Email Token Sending Failed', 'email' => $email]), request()->ip(), request()->userAgent());

                        return redirect()->route('login')->with(['EmailSendingFailed' => 'EmailSendingFailed']);
                    }
                }
        }
        $this->logActivity(json_encode(['activity' => 'Authorization Aborted']), request()->ip(), request()->userAgent());

        abort(422);
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
            'gender' => 'required|string|in:male,female',
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
        $generalInformations->save();

        switch ($gender) {
            case 'male':
                $user->assignRole('Parent(Father)');
                break;
            case 'female':
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
