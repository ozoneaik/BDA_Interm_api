<?php
namespace App\Services;

use App\Enums\UserTypes;
use App\Models\Notifications;
use App\Models\User;
use Illuminate\Support\Arr;

class NotificationService {
    public function getListNotification($input) {
        dd($input->all());
        $user_id = Arr::get($input, 'user_id') ?? null;
        return Notifications::query()
            ->with([
                'reference',
                'reference.user:name,id,picture_path',
                'reference.package:name,id',
                'user_package_logs',
                'user:id,name'
            ])
            ->when(is_null($user_id), function ($q){
                //admin
                $q->whereNull('user_id');
            })
            ->when(!is_null($user_id), function ($q) use ($user_id){
                //farmer
                $q->where('user_id', $user_id);

                Notifications::query()
                    ->where('notify_to_user_id', $user_id)
                    ->where('created_at', '<=', now())
                    ->update([
                       'read_at' => now()
                    ]);
            })
            ->orderByDesc('created_at')
            ->get();
    }

    public static function makeNotification(array $data)
    {
        return Notifications::query()->create($data);
    }

    public function getNotificationDetail($id)
    {
        $notification = Notifications::query()
            ->with(['reference', 'reference.user', 'reference.package', 'user'])
            ->where('id', $id)
            ->first();

        $notification->read_at = now();
        $notification->save();
        return $notification;
    }

    public function countNotifications()
    {
        $user = auth()->user();
        return Notifications::query()
            ->when($user->type === UserTypes::ADMIN->value, function($q){
                $q->where('notify_to', UserTypes::ADMIN->value)
                    ->whereNull('read_at');
            })
            ->when($user->type === UserTypes::FARMER->value, function($q) use ($user){
                $q->where('notify_to_user_id', $user->id)
                    ->whereNull('read_at');
            })
            ->count();
    }
}
