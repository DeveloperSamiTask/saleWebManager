<?php

namespace App\Models\Combos;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PurchaseLink extends Model
{
    use HasFactory;

    protected $table = 'purchases_link';

    protected $fillable = [
        'code',
        'lastname',
        'names',
        'document_type',
        'document_number',
        'phone',
        'date_purchase',
        'date_issue',
        'status',
        'user_id'
    ];

    public function combos()
    {
        return $this->hasMany(PurchaseCombo::class, 'purchase_link_id');
    }

    public static function getList($startDate, $endDate)
    {
        $coupons = self::with(['combos.combo', 'combos.members']) // Asegúrate que exista la relación 'combo' en combos
            ->whereBetween('date_purchase', [$startDate, $endDate])
            ->orderByDesc('date_purchase')
            ->get();

        $data = [];

        foreach ($coupons as $row) {
            $comboNames = $row->combos->pluck('combo.name')->implode(', ');

            $data[] = [
                'id'            => $row->id,
                'code'          => $row->code,
                'names'         => trim($row->names . ' ' . $row->lastname),
                'document' => $row->document_number,
                'combos'        => $comboNames, // Aquí se listan los nombres de combos
                'members'       => $row->combos->sum(fn($combo) => $combo->members->count()),
                'date_purchase' => $row->date_purchase,
                'date_issue'    => $row->date_issue,
                'status'        => $row->status ?? null,
            ];
        }

        return $data;
    }
}
