<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ticket extends Model
{
    use HasFactory;

    protected $table = 'qr_cupon';
    protected $primaryKey = 'id_coupon';
    protected $fillable = ['code_coupon', 'tickets', 'date_generate', 'date_used', 'method', 'method_data', 'code'];
    public $timestamps = false;
}
