<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CashAudit extends Model
{
    protected $table = 'cash_audit';
    protected $fillable = [
        'id',
        'user_id',
        'start_date',
        'end_date',
        'shift',
        'initial_amount',
        'total_amount',
        'final_amount',
        'total_tips',
        'created_at',
        'updated_at',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
