<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SupportController extends Controller
{
    /**
     * Show the support page.
     */
    public function index()
    {
        return view('aquaheart.support.index');
    }
}
