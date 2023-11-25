<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Casts\Attribute;

class WebApplication extends Model
{
    use HasFactory, HasApiTokens;

    protected $table = 'web_application_manager';
    protected $fillable = [
        'name',
        'url',
        'picture_path',
        'background_style',
        'button_style',
        'show_status'
    ];

    protected $hidden = [
        'remember_token',
    ];

    protected $appends = [
        'full_picture_path'
    ];

    public static function generateToken($web)
    {
        $web->tokens()->delete();
        $token = $web->createToken('web-token', ['webapp']);
        return $token;
    }

    public function fullPicturePath() : Attribute
    {
        return new Attribute(
            get: function () {
                return $this->picture_path
                    ? strpos($this->picture_path, 'https') > -1
                        ? $this->picture_path
                        : url('api/app_image?path='.$this->picture_path)
                    : null;
            }
        );
    }
}
