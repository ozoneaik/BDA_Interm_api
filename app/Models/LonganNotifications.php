<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LonganNotifications extends Model
{
    protected $table = 'longan_notifications';

    protected $fillable = [
        'type',
        'amount_due_date',
        'longan_id'
    ];

    public function longan()
    {
        return $this->belongsTo(Longans::class);
    }
}
