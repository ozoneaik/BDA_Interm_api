<?php

namespace App\Services;

use App\Enums\UserApproveStatus;
use App\Enums\UserPackageStatus;
use App\Enums\UserTypes;
use App\Models\Longans;
use App\Models\User;
use App\Models\UserPackage;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Hash;

class UserServices {

    protected FileService $fileService;
    public function __construct(FileService $fileService)
    {
        $this->fileService = $fileService;
    }
    public function profile()
    {
        $user_id = auth()->id();
        return User::find($user_id);
    }

    public function register($input) {
        $user = new User();
        $password = Hash::make(Arr::get($input, 'password'));
        $imageProfile = $input->file('picture');

        [$file_path, $file_name] = $this->fileService->storeFile($imageProfile, 'user');

        $user->fill([
            'name' => Arr::get($input, 'name'),
            'email' => Arr::get($input, 'email'),
            'password' => $password,
            'phone' => Arr::get($input, 'phone'),
            'picture_path' => $file_path,
            'type' => Arr::get($input, 'type')
        ]);
        $user->save();

        return $user;
    }

    public function getListUser($input, $user_type=UserTypes::ADMIN)
    {
        $search = Arr::get($input, 'search');
        return User::query()
            ->when($search, function (Builder $q) use ($search){
                $q->where('name', 'ilike', '%'.$search.'%');
            })
            ->where('type', $user_type)
            ->orderByDesc('created_at')
            ->get();
    }

    public function getUserDetail($id)
    {
//        dd(User::find($id));
        return User::find($id);
    }

    public function updateUser($input, $id)
    {
        $user = User::find($id);

        if(isset($user)) {
            $imageProfile = $input->file('picture');

            $file_path = $user->picture_path;
            if(isset($imageProfile) && gettype($imageProfile) === 'object') {
                [$file_path, $file_name] = $this->fileService->storeFile($imageProfile, 'user');
            }

            $user->fill([
                'name' => Arr::get($input, 'name') ?? $user->name,
                'email' => Arr::get($input, 'email') ?? $user->email,
                'phone' => Arr::get($input, 'phone') ?? $user->phone,
                'picture_path' => $file_path
            ]);
            $user->save();
            return $user;
        }

        return false;
    }

    public function loginWithLine($input)
    {
        $name = Arr::get($input, 'name');
        $email = Arr::get($input, 'email');
        $picture = Arr::get($input, 'picture_path');

        $user = User::query()
            ->where('email', $email)
            ->first();

        if($user){
            return [
                'is_register' => false,
                'user' => $user
            ];
        }else {
            $user = User::query()->create([
                'name' => $name,
                'email' => $email,
                'picture_path' => $picture,
                'type' => 'FARMER',
                'status' => UserApproveStatus::PENDING->value
            ]);

            return [
                'is_register' => true,
                'user' => $user
            ];
        }
    }

    public function deleteUser($id)
    {
        $user = User::find($id);
        if($user) {
            $user->delete();
            return true;
        }
        return false;
    }

    public function checkMaxPackage($user_id)
    {
        $count_tree = Longans::query()
            ->with('farms')
            ->whereHas('farms', function($q) use($user_id){
                $q->where('user_id', $user_id);
            })
            ->count();

            $package_tree = 0;
            $user_packages = UserPackage::query()
                ->with('package')
                ->where('user_id', $user_id)
                ->where('status', UserPackageStatus::APPROVED->value)
                ->get();

            foreach ($user_packages as $user_package) {
                if($user_package->package) {
                    $package_tree += $user_package->package->amount_of_longan;
                }
            }

            return [
              'amount_of_tree' => $count_tree,
              'package_tree' => $package_tree,
              'is_over_package' => $count_tree >= $package_tree //when true are over package
            ];
    }

    public function approveOrReject($id, $input)
    {
        $user = User::find($id);
        $status = Arr::get($input, 'is_approve') ? UserApproveStatus::APPROVED->value : UserApproveStatus::REJECTED->value;

        if($user){
            $user->status = $status;
            $user->save();
            return true;
        }

        return false;
    }
}
