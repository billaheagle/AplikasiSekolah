<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HandlingController extends Controller
{
    public function error403()
    {
    	return view('handling.page403');
    }

    public function error404()
    {
    	return view('handling.page404');
    }

    public function error500()
    {
    	return view('handling.page500');
    }

    public function error503()
    {
    	return view('handling.page503');
    }
}
