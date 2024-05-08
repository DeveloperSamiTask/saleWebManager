<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LogDNI extends Model
{
    use HasFactory;
    use HasFactory;
    protected $table = 'log_dni';
    protected $primaryKey = 'id_logdni';
    protected $fillable = ['detcart_id', 'names_ticket', 'user_send', 'dni_before','dni_after','status_change','user_acepted'];

}
