<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notifications extends Model
{
    use HasFactory;

    protected $table = 'notifications';

    protected $fillable = [
        'ref_id',
        'notify_to',
        'notify_type',
        'user_id',
        'read_at',
        'notify_from_user_id',
        'notify_to_user_id'
    ];

    public function reference()
    {
        return $this->belongsTo(UserPackage::class, 'ref_id', 'id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function user_package_logs()
    {
        return $this->belongsTo(UserPackageLogs::class, 'id', 'notification_id');
    }
}
