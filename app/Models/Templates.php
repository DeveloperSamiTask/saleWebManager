<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Templates extends Model
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

    public static function getTemplates()
    {
        $query = self::with(['company', 'promotion']);
        $templates =  $query->orderByDesc('created_at')->get();
        $data = [];
        foreach ($templates as $row) {
            $data[] = [
                'id' => $row->id,
                'company' =>  optional($row->company)->name,
                'company_id' =>  $row->company_id,
                'promotion' =>  optional($row->promotion)->name,
                'promotion_id' =>  $row->promotion_id,
                'text' => $row->content,
                'issue_date' => $row->created_at,
            ];
        }
        return $data;
    }
}
