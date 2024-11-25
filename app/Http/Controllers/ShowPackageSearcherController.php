<?php

namespace App\Http\Controllers;

use App\Models\Project;
use Illuminate\Http\Request;

class ShowPackageSearcherController extends Controller
{

    public function __invoke()
    {
        return view('package-searcher');
    }
}
