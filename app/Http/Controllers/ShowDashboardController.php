<?php

namespace App\Http\Controllers;

use App\Models\Project;

class ShowDashboardController extends Controller
{

    public function __invoke()
    {
        $projects = Project::orderBy('name')->get();

        return view('dashboard', compact('projects'));
    }
}
