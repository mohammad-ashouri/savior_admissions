<?php

namespace App\Http\Controllers\BranchInfo;

use App\Http\Controllers\Controller;
use App\Models\Branch\Interview;

class InterviewController extends Controller
{
    public function destroy($id)
    {
        $removeInterview = Interview::find($id)->delete();
        if (! $removeInterview) {
            return redirect()->back()
                ->with('error', 'Delete Failed!');
        }

        return redirect()->back()
            ->with('success', 'Interview deleted!');
    }
}
