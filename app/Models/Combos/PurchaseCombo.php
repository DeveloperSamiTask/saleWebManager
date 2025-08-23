<?php

namespace App\Models\Combos;

use App\Models\PromotionsLink;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PurchaseCombo extends Model
{
    use HasFactory;

    protected $table = 'purchase_combo';

    protected $fillable = [
        'purchase_link_id',
        'combo_id',
        'quantity',
    ];

    public function purchaseLink()
    {
        return $this->belongsTo(PurchaseLink::class, 'purchase_link_id');
    }

    public function combo()
    {
        return $this->belongsTo(PromotionsLink::class, 'combo_id');
    }

    public function members()
    {
        return $this->hasMany(PurchaseComboMember::class, 'purchase_combo_id');
    }

    public function validations()
    {
        return $this->hasMany(PurchaseComboValidation::class, 'purchase_link_combo_id');
    }
}
