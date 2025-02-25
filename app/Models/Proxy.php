<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Proxy extends Model
{
    use HasFactory;

    protected $table = 'apoderado_cliente';
    public $timestamps = false;

    public function client()
    {
        return $this->belongsTo(Client::class, 'proxy_client');
    }
}
