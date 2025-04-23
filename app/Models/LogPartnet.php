<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LogPartnet extends Model
{
    use HasFactory;
    protected $table = 'log_partnet';

    protected $fillable = ['code_partner', 'type_log', 'doc', 'user'];
    public $timestamps = true;

    
    public static function registerLog($code, $tipo, $doc)
    {
        self::create([
            'code_partner' => $code,
            'type_log'     => $tipo,
            'doc'          => $doc,
            'user'         => auth()->user()->usuario ?? 'Sistema',
        ]);
    }
}
