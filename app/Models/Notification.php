<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    use HasFactory;
    protected $table = 'notify';
    protected $primaryKey = 'id_notify';
    protected $fillable = ['code','title_notify', 'body_notify', 'url_notify', 'statusview_notify', 'statusclick_notify', 'type_notify'];

    public static function getNotify()
    {
        $notifications = Notification::orderBy('created_at', 'desc')->take(10)->get();
        return $notifications;
    }

    public static function newNotify($code, $title, $body, $type)
    {
        $notify = new Notification();
        $notify->code = $code;
        $notify->title_notify = $title;
        $notify->body_notify = $body;
        $notify->statusview_notify = 0;
        $notify->statusclick_notify = 0;
        $notify->type_notify = $type;
        $notify->save();
    }

    public static function updateNotify($id){

        $notify = Notification::find($id);
        $notify->statusview_notify = 1;
        $notify->save();
    }


}
