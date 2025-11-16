<?php

namespace App\Http\Controllers\Voice;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class MenuVoiceController extends Controller
{
    public function index()
    {
        return view('voice.menu');
    }
}