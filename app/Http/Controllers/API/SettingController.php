<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\SettingFormRequest;
use App\Services\SettingService;

class SettingController extends BaseAPIController
{
    protected SettingService $settingService;
    public function __construct(SettingService $settingService)
    {
        $this->settingService = $settingService;
    }
    public function index()
    {
        $setting = $this->settingService->getSettingValue();
        return $this->successResponse($setting);
    }

    public function store(SettingFormRequest $request)
    {
        $setting = $this->settingService->updateSetting($request);
        return $this->successResponse($setting);
    }
}
