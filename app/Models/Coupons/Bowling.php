<?php

namespace App\Models\Coupons;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bowling extends Model
{
    use HasFactory;

    protected $connection = 'lagranja_cupon';

    protected $table = 'tbl_bowling';
    protected $primaryKey = 'id_colegio';

    protected $fillable = [
        'int_stado',
        'txt_foto'
    ];

    public $timestamps = false;
}
