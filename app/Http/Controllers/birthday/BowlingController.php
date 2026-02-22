<?php

namespace App\Http\Controllers\birthday;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class BowlingController extends Controller
{
    public function birthdayView()
    {
        return view('birthday.bowling.index');
    }

    public function templateBirthdayView()
    {
        return view('birthday.bowling.template');
    }
}
