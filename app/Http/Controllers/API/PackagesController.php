<?php

namespace App\Http\Controllers\API;

use App\Services\PackageService;
use Illuminate\Http\Request;

class PackagesController extends BaseAPIController
{
    protected PackageService $packageService;
    public function __construct(PackageService $packageService)
    {
        $this->packageService = $packageService;
    }
    public function index()
    {
        $packages = $this->packageService->getPackages();
        return $this->successResponse($packages);
    }

    public function buyPackage(Request $request)
    {
        $res = $this->packageService->farmerBuyPackage($request);
        if($res) {
            return $this->successResponse($res);
        }else {
            return $this->errorResponse();
        }
    }

    public function approveOrRejectPackage(Request $request)
    {
        $res = $this->packageService->approveRejectPackage($request);
        return $res ? $this->successResponse($res) : $this->errorResponse();
    }
}
