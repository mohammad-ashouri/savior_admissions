<?php

namespace App\Http\Controllers\AuthControllers;

use App\Http\Controllers\Controller;
use App\Models\Country;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Mews\Captcha\Captcha;

class LoginController extends Controller
{
    protected $redirectTo = '/dashboard';

    public function getCaptcha(Captcha $captcha)
    {
        $captcha->builder()->build();
        session(['captcha' => $captcha->builder()->getPhrase()]);

        return $captcha->response();
    }

    public function __construct()
    {
        $this->middleware('guest')->except('logout');
        $this->middleware('web')->only('logout');
    }

    public function showLoginForm(): \Illuminate\Contracts\View\View|\Illuminate\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\Contracts\Foundation\Application
    {
        if (! \session('id')) {
            return view('Auth.login');
        }
        //        $nationalities=Country::select('id','nationality')->get();
        //        if (!Auth::check()) {
        //            return view('Auth.fake_signup',compact('nationalities'));
        //        }

        return redirect()->route('dashboard');
    }

    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'login-method' => 'required|in:mobile,email', // Uncomment if you want to include captcha validation
        ]);
        if ($validator->fails()) {
            $this->logActivity(json_encode(['activity' => $validator->errors()]), request()->ip(), request()->userAgent());

            return response()->json([
                'success' => false,
                'errors' => ['validator_errors' => $validator->errors()],
            ]);
        }

        switch (request('login-method')) {
            case 'email':
                $validator = Validator::make($request->all(), [
                    'email' => 'required|email|exists:users,email',
                    'password' => 'required', // Uncomment if you want to include captcha validation
                ]);

                if ($validator->fails()) {
                    $this->logActivity(json_encode(['activity' => 'Login Failed', 'validator_errors' => $validator->errors()]), request()->ip(), request()->userAgent());

                    return response()->json([
                        'success' => false,
                        'validator_errors' => $validator->errors(),
                    ]);
                }
                $user = User::where('email', $request->input('email'))->first();
                if (! password_verify($request->password, $user->password)) {
                    $this->logActivity(json_encode(['activity' => 'Login Failed', 'Entry Values' => $request->all(), 'errors' => 'Wrong Password']), request()->ip(), request()->userAgent());

                    return response()->json([
                        'success' => false,
                        'errors' => ['loginError' => 'Wrong email or password'],
                    ]);
                }
                break;
            case 'mobile':
                $validator = Validator::make($request->all(), [
                    'mobile' => 'required|exists:users,mobile',
                    'password' => 'required', // Uncomment if you want to include captcha validation
                ]);

                if ($validator->fails()) {
                    $this->logActivity(json_encode(['activity' => 'Login Failed', 'Entry Values' => $request->all(), 'validator_errors' => $validator->errors()]), request()->ip(), request()->userAgent());

                    return response()->json([
                        'success' => false,
                        'validator_errors' => $validator->errors(),
                    ]);
                }
                $user = User::where('mobile', $request->input('mobile'))->first();
                if (! password_verify($request->password, $user->password)) {
                    $this->logActivity(json_encode(['activity' => 'Login Failed', 'errors' => 'Wrong Password']), request()->ip(), request()->userAgent());

                    return response()->json([
                        'success' => false,
                        'errors' => ['loginError' => 'Wrong mobile or password'],
                    ]);
                }
                break;
        }

        $validator = Validator::make($request->all(), [
            'captcha' => 'required', // Uncomment if you want to include captcha validation
        ]);
        if ($validator->fails()) {
            $this->logActivity(json_encode(['activity' => 'Wrong Captcha', 'errors' => $validator->errors()]), request()->ip(), request()->userAgent());

            return response()->json([
                'success' => false,
                'validator_errors' => $validator->errors(),
            ]);
        }
        $captcha = $request->input('captcha');

        // Uncomment if you want to include captcha validation
        $sessionCaptcha = session('captcha')['key'];
        if (! password_verify($captcha, $sessionCaptcha)) {
            $this->logActivity(json_encode(['activity' => 'Login Failed (Wrong Captcha)', 'entered_values' => json_encode($request->all())]), request()->ip(), request()->userAgent());

            return response()->json([
                'success' => false,
                'errors' => ['captcha' => 'Captcha is wrong!'],
            ]);
        }

        $credentials = $request->only('email', 'password');
        $remember = $request->has('remember');

//        if (Auth::attempt($credentials, $remember)) {
            Session::put('id', $user->id);
            $this->logActivity(json_encode(['activity' => 'Login Succeeded', 'email' => $request->input('email')]), request()->ip(), request()->userAgent());

            return response()->json([
                'success' => true,
                'redirect' => route('dashboard'),
            ]);
//        }

//        return response()->json(['att']);
    }

    public function logout(): \Illuminate\Http\RedirectResponse
    {
        Auth::logout();
        session()->flush();

        return redirect()->route('login');
    }

    protected function authenticated(Request $request, $user): \Illuminate\Http\RedirectResponse
    {
        return redirect()->intended($this->redirectPath());
    }
}
