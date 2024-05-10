<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Notification;

class Notify extends Controller
{
    public function index()
    {
        $data = Notification::all();
        return response()->json($data);
    }
}
