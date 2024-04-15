<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Entries extends Model
{
    use HasFactory;
    protected $table = 'boleto';
    protected $primaryKey = 'intBoletoId';
    public $timestamps = false;
    
}
