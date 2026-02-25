<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function index()
    {
        return view('reports.index');
    }

    public function residents()
    {
        return view('reports.residents');
    }

    public function certificates()
    {
        return view('reports.certificates');
    }

    public function blotters()
    {
        return view('reports.blotters');
    }
}