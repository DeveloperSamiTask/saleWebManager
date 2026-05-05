<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PromotionsLink extends Model
{
    use HasFactory;
    
    protected $connection = 'mysql_paylink';
    public $table = 'promotions_link';

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
                'status' => $row->status ?? null, // Por si no existe ese campo
            ];
        }

        return $data;
    }
}
