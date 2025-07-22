<?php

namespace App\Models\Combos;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PurchaseComboMember extends Model
{
      use HasFactory;

    protected $table = 'purchase_combo_members';

    protected $fillable = [
        'purchase_combo_id',
        'name',
        'dni',
    ];

    public function purchaseCombo()
    {
        return $this->belongsTo(PurchaseCombo::class, 'purchase_combo_id');
    }
}
