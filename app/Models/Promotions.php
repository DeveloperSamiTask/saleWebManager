<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Promotions extends Model
{
    use HasFactory;


    public static function getPromotions($company_id)
    {
        return Promotions::where('company_id', $company_id)->get();
    }
}
