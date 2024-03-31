<?php

namespace App\Http\Controllers\Test;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ChartController extends Controller
{
    public function index()
    {
        return view('charts.index');
    }
}
