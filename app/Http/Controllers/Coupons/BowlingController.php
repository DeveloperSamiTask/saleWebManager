<?php

namespace App\Http\Controllers\Coupons;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class BowlingController extends Controller
{
    public function list()
    {
        $data['title'] = "Lista Pago Link";
        return view('bowling.index', $data);
    }
}
