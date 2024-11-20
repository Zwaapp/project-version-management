<?php

namespace App\Http\Controllers;

use App\Models\Project;

class ShowDashboardController extends Controller
{

    public function __invoke()
    {
        $projects = Project::all();

        return view('dashboard', compact('projects'));
    }
}
