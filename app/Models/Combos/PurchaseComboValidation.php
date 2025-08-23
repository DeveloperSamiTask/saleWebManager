<?php

namespace App\Models\Combos;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PurchaseComboValidation extends Model
{
    use HasFactory;

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
}
