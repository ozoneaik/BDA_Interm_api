<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;

class Longans extends Model
{
    protected $table = 'longans';

    protected $fillable = [
        'name',
        'type',
        'farm_id',
        'location',
        'specie',
        'trimming_at'
    ];

    protected $appends = [
        'full_picture_path'
    ];

    public function farms()
    {
        return $this->belongsTo(Farms::class, 'farm_id', 'id');
    }

    public function longan_notification()
    {
        return $this->hasMany(LonganNotifications::class);
    }

    public function fullPicturePath() : Attribute
    {
        return new Attribute(
            get: function () {
                $picture = $this->pictures;
                if($picture && count($picture) > 0){
                    return $picture[0]->picture_path ? url('api/longans_image?path='.$picture[0]->picture_path) : null;
                }else {
                    return null;
                }
            }
        );
    }

    public function pictures()
    {
        return $this->hasMany(LogansTreePicture::class);
    }
}
