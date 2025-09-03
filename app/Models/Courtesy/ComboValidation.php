<?php

namespace App\Models\Courtesy;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ComboValidation extends Model
{
    use HasFactory;

    protected $table = 'combo_validations_courtesy';

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
        return $this->belongsTo(Combo::class, 'purchase_link_combo_id');
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
