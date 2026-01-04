<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ExportAllController extends Controller
{
    public function index()
    {
        return view('exportall.index');
    }
}