<?php

namespace App\Models\Bowling;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Promotions extends Model
{
    use HasFactory;

    public $table = 'bowling_promotions';

    public static function show($startDate, $endDate)
    {
        $coupons = self::whereBetween('created_at', [$startDate, $endDate])
            ->orderByDesc('created_at')
            ->get();

        $data = [];

        foreach ($coupons as $row) {
            $data[] = [
                'id' => $row->id,
                'name' => $row->name,
                'price' => $row->price,
                'description' => $row->description,
                'members' => $row->members,
                'status' => $row->status ?? null,
            ];
        }

        return $data;
    }
}
