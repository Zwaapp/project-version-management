<?php

namespace App\Http\Controllers;

use App\Models\Project;
use Illuminate\Http\Request;

class ShowDashboardController extends Controller
{

    public function __invoke()
    {
        $projects = $this->getProjects();

        return view('dashboard', compact('projects'));
    }

    private function getProjects()
    {
        $query = Project::query();

        // Zoek naar naam
        if (request()->has('search') && request()->search != '') {
            $searchTerm = request()->search;
            $query->where('name', 'like', '%' . $searchTerm . '%')
                ->orWhere('type', 'like', '%' . $searchTerm . '%');
        }

        return $query->orderBy('name')->get();

    }
}
