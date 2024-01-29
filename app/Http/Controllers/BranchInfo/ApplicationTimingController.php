<?php

namespace App\Http\Controllers\BranchInfo;

use App\Http\Controllers\Controller;
use App\Models\Branch\ApplicationTiming;
use App\Models\User;
use Illuminate\Http\Request;

class ApplicationTimingController extends Controller
{
    public function index()
    {
        $me=User::find(session('id'));
        if ($me->hasRole('Super Admin')){
            $data=ApplicationTiming::orderBy('id','desc')->get();
        }
        return view('BranchInfo.ApplicationTimings',compact('data'));
    }
}
