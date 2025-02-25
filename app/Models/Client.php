<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    use HasFactory;
    protected $table = 'CLIENTE';
    protected $primaryKey = 'cClieCode';
    public $timestamps = false;

    public function partners()
    {
        return $this->hasMany(Partner::class, 'cClieCode', 'cClieCode');
    }

    public function proxy()
    {
        return $this->hasOne(Proxy::class, 'proxy_client');
    }
}
