<?php

namespace App\Models\Bowling;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Combo extends Model
{
    use HasFactory;

    protected $table = 'bowling_combo';

    protected $fillable = [
        'purchase_link_id',
        'combo_id',
        'quantity',
    ];

    public function purchaseLink()
    {
        return $this->belongsTo(Link::class, 'purchase_link_id');
    }

    public function combo()
    {
        return $this->belongsTo(Promotions::class, 'combo_id');
    }

    public function members()
    {
        return $this->hasMany(ComboMember::class, 'purchase_combo_id');
    }

    public function validations()
    {
        return $this->hasMany(ComboValidation::class, 'purchase_link_combo_id');
    }
}
