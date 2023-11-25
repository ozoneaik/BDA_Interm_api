<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserPackageLogs extends Model
{
    use HasFactory;

    protected $table = 'user_package_logs';

    protected $fillable = [
      'user_packages_id',
      'notification_id',
      'approved_by',
      'status'
    ];

    public function notifications()
    {
        return $this->hasMany(Notifications::class);
    }
}
