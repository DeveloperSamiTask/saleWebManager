<?php

namespace App\Models\Combos;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PurchaseComboValidation extends Model
{
    use HasFactory;

    protected $connection = 'mysql_paylink';
    protected $table = 'combo_validations';

    protected $fillable = [
        'purchase_link_combo_id',
        'validated_qty',
        'validated_at',
        'validated_by',
    ];

    protected $casts = [
        'validated_at' => 'datetime',
    ];

    public function combo()
    {
        return $this->belongsTo(PurchaseCombo::class, 'purchase_link_combo_id');
    }

    // Relación con el usuario que validó
    public function validator()
    {
        return $this->belongsTo(User::class, 'validated_by');
    }

    public static function getList($startDate, $endDate)
    {
        $combos = self::with([
            'combo.purchaseLink',
            'combo',
            'validator'
        ])
            ->whereBetween('validated_at', [$startDate, $endDate])
            ->get();

        $data = [];

        foreach ($combos as $row) {
            $data[] = [
                'code'          => $row->combo->purchaseLink->code ?? 'N/A',
                'combo_name'    => $row->combo->combo->name ?? 'N/A',
                'quantity'      => (string) $row->validated_qty,
                'validated_at'  => $row->validated_at ? $row->validated_at->format('Y-m-d H:i:s') : null,
                'validator'     => $row->validator ? $row->validator->usuario : 'N/A',
            ];
        }

        return $data;
    }
}
