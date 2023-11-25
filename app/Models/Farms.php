<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
class Farms extends Model
{
    protected $table = 'farms';
    protected $fillable = [
        'name',
        'amount_of_rai',
        'amount_of_tree',
        'age_of_rai',
        'amount_of_square_wa',
        'species',
        'location',
        'trimming_date',
        'user_id',
        'picture_path',
        'amount_of_square_meters',
        'polygons'
    ];

    protected $appends = [
      'full_picture_path',
        'amount_of_tree'
    ];

    protected $casts = [
      'polygons' => 'json'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function longans()
    {
        return $this->hasMany(Longans::class, 'farm_id', 'id');
    }

    public function fullPicturePath(): Attribute
    {
        return new Attribute(
            get: function () {
                return $this->picture_path ? url('api/farm_image?path='.$this->picture_path) : null;
            }
        );
    }

    public function amountOfTree(): Attribute
    {
        return new Attribute(
            get: function () {
                return Longans::query()
                    ->where('farm_id', $this->id)
                    ->count();
            }
        );
    }
}
