<?php

namespace App\Http\Controllers;

use App\Models\Designation;

class DesignationController extends Controller
{
    public function index()
    {
        return response()->json(Designation::orderBy('name')->get());
    }
}



