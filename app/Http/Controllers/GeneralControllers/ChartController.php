<?php

namespace App\Http\Controllers\GeneralControllers;

use App\Http\Controllers\Controller;
use App\Traits\ChartFunctions;
class ChartController extends Controller
{
    public static function registeredStudentsInLastAcademicYear()
    {
        $data = ChartFunctions->registeredStudentsInLastAcademicYear();

        return view('components.charts.pie', compact('data'));
    }
}
