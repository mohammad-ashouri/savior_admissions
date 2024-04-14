<?php

namespace App\Http\Controllers\AuthControllers;

use App\Http\Controllers\Controller;
use App\Mail\SendRegisterToken;
use App\Models\Auth\RegisterToken;
use App\Models\GeneralInformation;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class SignupController extends Controller
{
    public function index()
    {
        return view('Auth.Signup.authorization');
    }

    public function authorization(Request $request)
    {
        switch (request('signup-method')) {
            case 'Mobile':
                $validator = Validator::make($request->all(), [
                    'mobile' => [
                        'required',
                        'numeric',
                        'digits:11',
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
                break;
            default:
                abort(500);
        }

        if ($validator->fails()) {
            $this->logActivity(json_encode(['activity' => 'Wrong Entered Values For Signup', 'errors' => json_encode($validator), 'values' => $request->all()]), request()->ip(), request()->userAgent());
            $errors = $validator->errors();
            $errorMessage = '';

            if ($errors->has('mobile')) {
                $errorMessage = 'The entered mobile number is not valid.';
            } elseif ($errors->has('email')) {
                $errorMessage = 'The entered email address is not valid.';
            } else {
                $errorMessage = 'An internal server error occurred.';
            }

            return redirect()->route('login')->withErrors(['errors' => $errorMessage]);
        }

        switch (request('signup-method')) {
            case 'Mobile':
                $token = preg_replace('/[\/\\.]/', '', Str::random(32));
                $tokenEntry = new RegisterToken();
                $tokenEntry->register_method = 'Mobile';
                $tokenEntry->value = $request->mobile;
                $tokenEntry->token = $token;
                $tokenEntry->status = 0;
                $tokenEntry->save();

                $valueToSend = 'Your registration link is: '.env('APP_URL').'/new-account/'.$token."\nYou have one hour to register.\nSavior Schools Support";

                $this->sendSms($request->mobile, $valueToSend);

                return redirect()->route('login')->with(['SMSSent' => 'SMSSent']);
            case 'Email':
                $email = $request->email;

                $checkIfEmailExists = User::where('email', $email)->exists();
                if ($checkIfEmailExists) {
                    $errorMessage = 'The entered email address already exists.';

                    return redirect()->route('login')->withErrors(['errors' => $errorMessage]);

                } else {
                    $mailSend = Mail::to($email)->send(
                        new SendRegisterToken($email)
                    );

                    if ($mailSend) {
                        return redirect()->route('login')->with(['EmailSent' => 'EmailSent']);
                    } else {
                        return redirect()->route('login')->with(['EmailSendingFailed' => 'EmailSendingFailed']);
                    }
                }
        }

        abort(422);
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
            'gender' => 'required|string|in:male,female',
            'token' => 'required|exists:register_tokens,token',
        ]);

        if ($validator->fails()) {
            $this->logActivity(json_encode(['activity' => 'Wrong Entered Values For Signup', 'errors' => json_encode($validator), 'values' => $request->all()]), request()->ip(), request()->userAgent());

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
        $user->save();

        $gender = $request->gender;

        $generalInformations = new GeneralInformation();
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

        return redirect()->route('login')
            ->with('success', 'You registered successfully. Please login.');
    }
}
