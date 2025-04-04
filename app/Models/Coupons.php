<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Coupons extends Model
{
    use HasFactory;

    public function company()
    {
        return $this->belongsTo(Companies::class);
    }

    public function promotion()
    {
        return $this->belongsTo(Promotions::class);
    }

    public static function getCoupons($startDate, $endDate)
    {
        $query = self::with(['company', 'promotion']);

        $query->whereBetween('created_at', [$startDate, $endDate]);
        $coupons =  $query->orderByDesc('created_at')->get();
        $data = [];
        foreach ($coupons as $row) {

            $data[] = [
                'id' => $row->id,
                'code' => $row->code,
                'client' => $row->father_surname . " " . $row->mother_surname . " " . $row->names,
                'document' => $row->number_doc,
                'company' =>  optional($row->company)->name,
                'status' => $row->status,
                'expired_date' => $row->expired_date,
                'issue_date' => $row->created_at,
                'used_date' => $row->used_date,
            ];
        }

        return $data;
    }
}
