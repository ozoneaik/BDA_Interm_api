<?php
namespace App\Services;
use App\Models\Setting;
use Illuminate\Support\Arr;

class SettingService {

    public function getSettingValue()
    {
        return Setting::query()
            ->orderByDesc('updated_at')
            ->first();
    }
    public function updateSetting($input)
    {
        $setting = Setting::query()
            ->orderByDesc('updated_at')
            ->first();

        if(!$setting) {
            $setting = new Setting();
        }

        $setting->fill([
            'amount_of_longan' => Arr::get($input, 'amount_of_longan'),
            'distance' => Arr::get($input, 'distance')
        ]);
        $setting->save();
        return $setting;
    }
}
