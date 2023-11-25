<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserPackage extends Model
{
    use HasFactory;
    protected $table = 'user_packages';

    protected $fillable = [
      'user_id',
      'package_id',
      'status',
      'approved_by',
      'approved_at'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function package()
    {
        return $this->belongsTo(Packages::class, 'package_id', 'id');
    }

    public function notification()
    {
        return $this->hasMany(Notifications::class, 'ref_id', 'id');
    }
}
