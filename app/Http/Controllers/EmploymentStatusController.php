<?php

namespace App\Http\Controllers;

use App\Models\EmploymentStatus;

class EmploymentStatusController extends Controller
{
    public function index()
    {
        return response()->json(EmploymentStatus::orderBy('name')->get());
    }
}



