<?php

namespace App\Http\Controllers\BranchInfo;

use App\Http\Controllers\Controller;
use App\Models\Branch\ApplicationTiming;
use App\Models\User;
use Illuminate\Http\Request;

class ApplicationTimingController extends Controller
{
    function __construct()
    {
        $this->middleware('permission:application-timing-list', ['only' => ['index']]);
        $this->middleware('permission:application-timing-create', ['only' => ['create', 'store']]);
        $this->middleware('permission:application-timing-edit', ['only' => ['edit', 'update']]);
        $this->middleware('permission:application-timing-delete', ['only' => ['destroy']]);
        $this->middleware('permission:application-timing-search', ['only' => ['searchApplicationTiming']]);
    }

    public function index()
    {
        $me=User::find(session('id'));
        if ($me->hasRole('Super Admin')){
            $data=ApplicationTiming::orderBy('id','desc')->get();
        }
        return view('BranchInfo.ApplicationTimings.index',compact('data'));
    }
}
