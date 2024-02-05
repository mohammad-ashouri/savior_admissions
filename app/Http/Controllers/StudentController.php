<?php

namespace App\Http\Controllers;

use App\Models\StudentExtraInformation;
use App\Models\StudentInformation;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class StudentController extends Controller
{
    function __construct()
    {
        $this->middleware('permission:childes-list', ['only' => ['index']]);
        $this->middleware('permission:childes-create', ['only' => ['create', 'store']]);
        $this->middleware('permission:childes-edit', ['only' => ['edit', 'update']]);
        $this->middleware('permission:childes-delete', ['only' => ['destroy']]);
        $this->middleware('permission:childes-search', ['only' => ['search','show']]);
        $this->middleware('permission:change-student-information', ['only' => ['changeInformation']]);
    }

    public function changeInformation(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'current_identification_code' => 'required',
            'current_identification_type' => 'required|exists:current_identification_types,id',
            'current_nationality' => 'required|exists:countries,id',
            'father' => 'required|exists:users,id',
            'guardian' => 'required|exists:users,id',
            'guardian_student_relationship' => 'required|exists:guardian_student_relationships,id',
            'mother' => 'required|exists:users,id',
            'school' => 'required|exists:schools,id',
            'status' => 'required|exists:student_statuses,id',
            'user_id' => 'required|exists:users,id',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator], 422);
        }
        $extraInformationTitles = $request->title;
        $extraInformationDescriptions = $request->description;
        if (count($extraInformationTitles) != count($extraInformationDescriptions)) {
            return response()->json(['error' => 'Extras count values is not same'], 422);
        }

        $user = User::find($request->user_id);

        $studentInformation = [
            'school_id' => $request->school,
        ];
        $userAdditionalInformation = json_decode($user->additional_information, true) ?? [];
        $userAdditionalInformation = array_merge($userAdditionalInformation, $studentInformation);
        $user->additional_information = json_encode($userAdditionalInformation);
        $user->save();

        $studentInformation = StudentInformation::firstOrCreate(
            [
                'student_id' => $user->id,
            ]
        );

        $studentInformation->parent_father_id = $request->father;
        $studentInformation->parent_mother_id = $request->mother;
        $studentInformation->guardian = $request->guardian;
        $studentInformation->guardian_student_relationship = $request->guardian_student_relationship;
        $studentInformation->current_nationality = $request->current_nationality;
        $studentInformation->current_identification_type = $request->current_identification_type;
        $studentInformation->current_identification_code = $request->current_identification_code;
        $studentInformation->status = $request->status;
        $studentInformation->save();

        StudentExtraInformation::where('student_informations_id', $studentInformation->id)->delete();

        foreach ($extraInformationTitles as $index => $titles) {
            $studentExtraInformation = new StudentExtraInformation();
            $studentExtraInformation->student_informations_id = $studentInformation->id;
            $studentExtraInformation->name = $titles;
            $studentExtraInformation->description = $extraInformationDescriptions[$index];
            $studentExtraInformation->save();
        }

        $this->logActivity('Student information saved successfully => ' . $request->user_id, request()->ip(), request()->userAgent(), session('id'));
        return response()->json(['success' => 'Student information saved successfully!'], 200);
    }
}
