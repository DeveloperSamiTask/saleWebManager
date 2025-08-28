<?php

namespace App\Models\Coupons;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bowling extends Model
{
    use HasFactory;

    protected $connection = 'lagranja_cupon';

    protected $table = 'tbl_bowling';
    protected $primaryKey = 'id_Colegio';

    protected $fillable = [
        'txt_foto',
        'int_stado',
        'int_retoque'
    ];

    public $timestamps = false;
}
