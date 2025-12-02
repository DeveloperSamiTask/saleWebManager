<?php

namespace App\Models\Bowling;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ComboMember extends Model
{
    use HasFactory;

    protected $table = 'bowling_combo_members';

    protected $fillable = [
        'purchase_combo_id',
        'name',
        'dni',
        'status_entrie'
    ];

    public function purchaseCombo()
    {
        return $this->belongsTo(Combo::class, 'purchase_combo_id');
    }
}
