<?php

namespace App\Services;
use App\Enums\NotificationType;
use App\Enums\UserPackageStatus;
use App\Enums\UserTypes;
use App\Models\Packages;
use App\Models\User;
use App\Models\UserPackage;
use App\Models\UserPackageLogs;
use Illuminate\Support\Arr;

class PackageService {
    public function getPackages() {
        return Packages::all();
    }

    public function farmerBuyPackage($input)
    {
        $user = User::find(auth()->id());
        if($user) {
            $user_package = new UserPackage();
            $user_package->user_id = $user->id;
            $user_package->package_id = Arr::get($input, 'id');
            $user_package->status = UserPackageStatus::PENDING->value;
            $user_package->save();

            $data_noti_admin = $this->dataNotification($user_package, null, UserTypes::ADMIN->value);
            NotificationService::makeNotification($data_noti_admin);

            $data_noti_farmer = $this->dataNotification($user_package, $user->id, UserTypes::FARMER->value);
            $noti_farmer = NotificationService::makeNotification($data_noti_farmer);

            UserPackageLogs::query()->create([
                'status' => UserPackageStatus::PENDING->value,
                'user_packages_id' => $user_package->id,
                'notification_id' => $noti_farmer->id,
                'approved_by' => null
            ]);

            return true;
        }

        return false;
    }

    public function approveRejectPackage($input)
    {
        $is_approve = Arr::get($input, 'is_approve');
        $user_package_id = Arr::get($input, 'id');
        $status =  $is_approve ? UserPackageStatus::APPROVED : UserPackageStatus::REJECTED;

        $user_package = UserPackage::find($user_package_id);

        if($user_package) {
            $user_package->fill([
                'approved_by' => auth()->id(),
                'approved_at' => now(),
                'status' => $status
            ]);
            $user_package->save();

            $data_noti = $this->dataNotification($user_package, $user_package->user_id, UserTypes::FARMER->value);
            $noti_farmer = NotificationService::makeNotification($data_noti);

            UserPackageLogs::query()->create([
                'status' => $status,
                'user_packages_id' => $user_package->id,
                'notification_id' => $noti_farmer->id,
                'approved_by' => null
            ]);
            return $user_package;
        }

        return false;
    }

    public function dataNotification($user_package, $user_id, $user_type) {
        $data = [
            'ref_id' => $user_package->id,
            'notify_type' => NotificationType::PACKAGE->value,
            'notify_to' => $user_type,
            'user_id' => $user_id ?? null,
            'notify_from_user_id' => auth()->id(),
            'notify_to_user_id' => $user_id
        ];

        return $data;
    }
}
