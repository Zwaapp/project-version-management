<?php

namespace App\Http\Controllers;

use App\Models\Project;
use Illuminate\Http\Request;

class ShowDashboardController extends Controller
{

    public function __invoke()
    {
        return view('dashboard');
    }
}
