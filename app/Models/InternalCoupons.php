<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InternalCoupons extends Model
{
    use HasFactory;
    protected $table = 'internal_coupons';
    protected $fillable = ['description', 'names', 'document', 'user_send', 'status', 'date_use', 'file'];


    public function user()
    {
        return $this->belongsTo(User::class, 'user_send');
    }

    public static function getTableLogs($startDate, $endDate)
    {
        $query = self::query();
        if ($startDate && $endDate) {
            $query->whereBetween('created_at', [$startDate, $endDate]);
        }
    
        $log = $query->orderByDesc('created_at')->get();
    
        $data = [];
        foreach ($log as $row) {
            $data[] = [
                'id' => $row->id,
                'description' => $row->description,
                'names' => $row->names,
                'document' => $row->document,
                'user_send' => optional($row->user)->usuario,
                'status' => $row->status,
                'file' => $row->file,
                'date_use' => Carbon::parse($row->date_use)->locale('es')->isoFormat('D MMMM YYYYº'),
                'date_insert' => Carbon::parse($row->created_at)->locale('es')->isoFormat('D MMMM YYYY HH:mm:ss'),
            ];
        }
    
        return $data;
    }
}
