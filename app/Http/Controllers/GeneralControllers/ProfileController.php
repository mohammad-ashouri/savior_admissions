<?php

namespace App\Http\Controllers\GeneralControllers;

use App\Http\Controllers\Controller;
use App\Models\Catalogs\DocumentType;
use App\Models\Country;
use App\Models\Document;
use App\Models\GeneralInformation;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProfileController extends Controller
{
    function __construct()
    {
        $this->middleware('permission:access-user-role', ['only' => ['changeUserRole']]);
    }


    public function index(): \Illuminate\Contracts\View\View|\Illuminate\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\Foundation\Application
    {
        $me = User::find(session('id'));
        $myGeneralInformation = GeneralInformation::where('user_id', $me->id)->with('user')->first();
        $personnelPhotoType=DocumentType::where('name','Personal picture')->first();
        $myDocuments = Document::where('user_id', $me->id)->where('document_type_id',$personnelPhotoType->id)->latest()->first();
        $countries=Country::get();
        return view('GeneralPages.profile', compact('myGeneralInformation','myDocuments','countries'));
    }

    public function editMyProfile(Request $request)
    {
        $this->validate($request, [
            'father-name' => 'required|string',
            'gender' => 'required|string',
            'Birthdate' => 'required|string',
            'Nationality' => 'required|exists:countries,id',
            'birthplace' => 'required|exists:countries,id',
            'PassportNumber' => 'required|string',
            'FaragirCode' => 'required|string',
            'Country' => 'required|exists:countries,id',
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
                'nationality' => $request->input('Nationality'),
                'birthplace' => $request->input('birthplace'),
                'faragir_code' => $request->input('FaragirCode'),
                'passport_number' => $request->input('PassportNumber'),
                'country' => $request->input('Country'),
                'state_city' => $request->input('city'),
                'address' => $request->input('address'),
                'phone' => $request->input('phone'),
                'postal_code' => $request->input('zip/postalcode'),
                'status' => 1,
                'adder' => session('id'),
                'editor' => session('id'),
            ]
        );
        if ($generalInformation) {
            $this->logActivity(json_encode(['activity' => 'Profile Updated']), request()->ip(), request()->userAgent());

            return redirect()->back()->withSuccess('Profile updated successfully!');
        }
    }

    public function changeUserGeneralInformation(Request $request): \Illuminate\Http\JsonResponse
    {
        $this->validate($request, [
            'first_name_fa' => 'required|string',
            'last_name_fa' => 'required|string',
            'first_name_en' => 'required|string',
            'last_name_en' => 'required|string',
            'father_name' => 'required|string',
            'gender' => 'required|string',
            'Birthdate' => 'required|date',
            'nationality' => 'required|exists:countries,id',
            'birthplace' => 'required|exists:countries,id',
            'PassportNumber' => 'required|string',
            'FaragirCode' => 'required|string',
            'Country' => 'required|exists:countries,id',
            'city' => 'required|string',
            'mobile' => 'required',
            'address' => 'required|string|max:100',
            'phone' => 'required',
            'postalcode' => 'required|integer',
            'email' => 'required|email',
            'user_id' => 'required|exists:users,id',
        ]);

        $input = $request->all();
        $user = User::find($input['user_id']);
        $userGeneralInformation = GeneralInformation::where('user_id', $user->id)->first();
        $userGeneralInformation->first_name_fa = $input['first_name_fa'];
        $userGeneralInformation->last_name_fa = $input['last_name_fa'];
        $userGeneralInformation->first_name_en = $input['first_name_en'];
        $userGeneralInformation->last_name_en = $input['last_name_en'];
        $userGeneralInformation->gender = $input['gender'];
        $userGeneralInformation->father_name = $input['father_name'];
        $userGeneralInformation->birthdate = $input['Birthdate'];
        $userGeneralInformation->country = $input['Country'];
        $userGeneralInformation->nationality = $input['nationality'];
        $userGeneralInformation->birthplace = $input['birthplace'];
        $userGeneralInformation->passport_number = $input['PassportNumber'];
        $userGeneralInformation->faragir_code = $input['FaragirCode'];
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
        $user->editor = session('id');
        $user->save();

        $userGeneralInformation->editor = session('id');
        $userGeneralInformation->save();
        $this->logActivity(json_encode(['activity' => 'Profile Updated By Admin','user_id'=>$user->id]), request()->ip(), request()->userAgent());
        return response()->json(['success' => 'Profile updated!'], 200);
    }

    public function changeUserRole(Request $request): \Illuminate\Http\JsonResponse
    {
        $user = User::find($request->input('user_id'));
        DB::table('model_has_roles')->where('model_id', $user->id)->delete();
        $user->syncRoles($request->input('role'));
        $this->logActivity(json_encode(['activity' => 'Rules Updated By Admin','user_id'=>$user->id]), request()->ip(), request()->userAgent());
        return response()->json(['success' => 'Rules updated! <br> Please refresh the page to display additional information'], 200);
    }
}
