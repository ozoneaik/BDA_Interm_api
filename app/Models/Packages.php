<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Packages extends Model
{
    use HasFactory;
    protected $table='packages';

    protected $fillable = [
      'name',
      'detail',
      'price',
      'amount_of_longan',
      'other'
    ];

    protected $casts = [
        'other' => 'json'
    ];
}
