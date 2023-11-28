<?php

namespace App\Http\Controllers\GeneralControllers;

use App\Http\Controllers\Controller;
use App\Models\GeneralInformation;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProfileController extends Controller
{
    public function index()
    {
        $myInfo = User::find(session('id'));
        $myGeneralInformation = GeneralInformation::where('user_id', $myInfo->id)->with('user')->first();
        return view('GeneralPages.profile', compact('myGeneralInformation'));
    }

    public function editMyProfile(Request $request)
    {
        $this->validate($request, [
            'father-name' => 'required|string',
            'gender' => 'required|string',
            'Birthdate' => 'required|string',
            'Country' => 'required|string',
            'city' => 'required|string',
            'address' => 'required|string',
            'phone' => 'required|string',
            'zip/postalcode' => 'required|string',
        ]);

        $generalInformation = GeneralInformation::where('user_id', session('id'))->update(
            [
                'user_id' => session('id'),
                'father_name' => $request->input('father-name'),
                'gender' => $request->input('gender'),
                'birthdate' => $request->input('Birthdate'),
                'country' => $request->input('Country'),
                'state_city' => $request->input('city'),
                'address' => $request->input('address'),
                'phone' => $request->input('phone'),
                'postal_code' => $request->input('zip/postalcode'),
                'adder' => session('id'),
                'editor' => session('id'),
            ]
        );
        if ($generalInformation) {
            return redirect()->back()->withSuccess('Profile updated successfully!');
        }
    }

    public function changeUserGeneralInformation(Request $request)
    {
        $this->validate($request, [
            'first_name' => 'required|string',
            'last_name' => 'required|string',
            'father_name' => 'required|string',
            'gender' => 'required|string',
            'Birthdate' => 'required|date',
            'Country' => 'required|string',
            'city' => 'required|string',
            'mobile' => 'required',
            'address' => 'required|string|max:100',
            'phone' => 'required',
            'postalcode' => 'required|integer',
            'email' => 'required|email',
            'roles' => 'required',
            'user_id' => 'required',
        ]);

        $input = $request->all();
        $user = User::find($input['user_id']);
        $userGeneralInformation = GeneralInformation::where('user_id', $user->id)->first();
        $user->name = $input['first_name'];
        $user->family = $input['last_name'];
        $userGeneralInformation->gender = $input['gender'];
        $userGeneralInformation->father_name = $input['father_name'];
        $userGeneralInformation->birthdate = $input['Birthdate'];
        $userGeneralInformation->country = $input['Country'];
        $userGeneralInformation->state_city = $input['city'];
        $userGeneralInformation->address = $input['address'];
        $userGeneralInformation->phone = $input['phone'];
        $userGeneralInformation->postal_code = $input['postalcode'];

        $checkEmail = User::where('email', $input['email'])->where('id', '!=', $user->id)->exists();
        $checkMobile = User::where('mobile', $input['mobile'])->where('id', '!=', $user->id)->exists();

        if ($checkEmail) {
            return response()->json(['error' => 'Email exists! try another email'], 500);
        }
        if ($checkMobile) {
            return response()->json(['error' => 'Mobile exists! try another mobile'], 500);
        }

        $user->email = $input['email'];
        $user->mobile = $input['mobile'];
        $user->save();
        $userGeneralInformation->save();

        DB::table('model_has_roles')->where('model_id', $user->id)->delete();
        $user->syncRoles($request->input('roles'));

        return response()->json(['success' => 'Profile updated!'], 200);

    }
}
