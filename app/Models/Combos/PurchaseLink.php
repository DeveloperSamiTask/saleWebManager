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
        'user_id',
        'user_active',
        'activate_date',
        'observation'
    ];

    public function combos()
    {
        return $this->hasMany(PurchaseCombo::class, 'purchase_link_id');
    }

    public static function getList($startDate, $endDate, $isChecked)
    {
        $filterField = $isChecked == '1' ? 'date_issue' : 'date_purchase';

        $coupons = self::with(['combos.combo', 'combos.members'])
            ->whereBetween($filterField, [$startDate, $endDate])
            ->orderByDesc($filterField)
            ->get();

        $data = [];

        foreach ($coupons as $row) {
            $comboNames = $row->combos->map(function ($combo) {
                return '(' . $combo->quantity . ') ' . $combo->combo->name;
            })->implode(', ');

            $totalMembers = 0;
            $validatedMembers = 0;
            $totalAmount = 0;

            foreach ($row->combos as $combo) {
                $members = $combo->members;
                $totalMembers += $members->count();
                $validatedMembers += $members->where('status_entrie', 'used')->count();

                $price = $combo->combo->price ?? 0;
                $quantity = $combo->quantity ?? 0;
                $totalAmount += $price * $quantity;
            }

            $data[] = [
                'id'                => $row->id,
                'code'              => $row->code,
                'names'             => trim($row->names . ' ' . $row->lastname),
                'document'          => $row->document_number,
                'combos'            => $comboNames,
                'members'           => (string) $totalMembers,
                'validated_members' => (string) $validatedMembers,
                'amount'            => number_format($totalAmount, 2, '.', ''), // Ej. 120.00
                'date_purchase'     => $row->date_purchase,
                'date_issue'        => $row->date_issue,
                'status'            => $row->status ?? null,
            ];
        }

        return $data;
    }
}
