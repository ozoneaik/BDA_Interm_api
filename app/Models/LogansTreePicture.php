<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LogansTreePicture extends Model
{
    use HasFactory;
    protected $table = 'picture_longan_trees';

    protected $fillable = [
      'picture_path',
      'directions',
      'seq',
      'longans_id'
    ];

    protected $appends = [
        'full_picture_path'
    ];

    public function longan() {
        return $this->belongsTo(Longans::class, 'longans_id', 'id');
    }

    public function fullPicturePath(): Attribute
    {
        return new Attribute(
            get: function () {
                return $this->picture_path ? url('api/longans_image?path='.$this->picture_path) : null;
            }
        );
    }
}
