<?php

namespace App\Http\Controllers\GeneralControllers;

use App\Http\Controllers\Controller;
use App\Models\GeneralInformation;
use App\Models\User;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
    public function index()
    {
        $myInfo = User::find(session('id'));
        $myGeneralInformation = GeneralInformation::where('user_id',$myInfo->id)->with('user')->first();
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

        $generalInformation = GeneralInformation::where('user_id',session('id'))->update(
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
}
