<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Enums\UserPackageStatus;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'phone',
        'picture_path',
        'type',
        'status'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    protected $appends = [
        'full_picture_path',
        'count_of_farm',
        'count_of_tree',
        'count_of_package'
    ];

    public static function generateToken($user)
    {
        $user->tokens()->delete();
        $token = $user->createToken('login-token', ['webapp']);
        return $token;
    }

    public function farms()
    {
        return $this->hasMany(Farms::class);
    }

    public function allAmountOfRaiAndTree()
    {
        $farms = $this->farms;
        $farm_size = 0;
        $farm_tree = 0;
        $count_farm = $farms && count($farms) > 0 ? count($farms) : 0;
        if($farms && count($farms) > 0) {
            foreach ($farms as $farm) {
                $farm_size += $farm->amount_of_rai;
                $farm_tree += $farm->amount_of_tree;
            }
        }

        return[
           'size' => intval($farm_size),
           'tree' => intval($farm_tree),
            'farm' => $count_farm
        ];
    }

    public function fullPicturePath() : Attribute
    {
        return new Attribute(
            get: function () {
                return $this->picture_path
                    ? strpos($this->picture_path, 'https') > -1
                        ? $this->picture_path
                        : url('api/user_image?path='.$this->picture_path)
                    : null;
            }
        );
    }

    public function countOfFarm() : Attribute
    {
        return new Attribute(
            get: function () {
                return $this->allAmountOfRaiAndTree()['farm'];
            }
        );
    }

    public function countOfTree() : Attribute
    {
        return new Attribute(
            get: function () {
                return $this->allAmountOfRaiAndTree()['tree'];
            }
        );
    }

    public function countOfPackage() : Attribute
    {
        return new Attribute(
            get: function () {
                return $this->user_package(UserPackageStatus::APPROVED)->count();
            }
        );
    }

    public function user_package($status=null)
    {
        return $this->hasMany(UserPackage::class)
            ->when($status, function ($q) use ($status) {
                $q->where('status', $status->value);
            });
    }
}
