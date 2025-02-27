<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Proxy extends Model
{
    use HasFactory;

    protected $table = 'apoderado_cliente';
    protected $primaryKey = 'proxy_id';
    public $timestamps = false;
    protected $fillable = ['proxy_pattername', 'proxy_mattername', 'proxy_names', 'proxy_doc'];

    public function client()
    {
        return $this->belongsTo(Client::class, 'proxy_client');
    }
}
