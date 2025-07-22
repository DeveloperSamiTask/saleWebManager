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
        'user_id'
    ];

    public function combos()
    {
        return $this->hasMany(PurchaseCombo::class, 'purchase_link_id');
    }
}
