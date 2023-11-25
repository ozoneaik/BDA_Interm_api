<?php

namespace App\Http\Controllers\API;

use App\Enums\UserTypes;
use App\Helpers\ImageHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateFarmerFormRequest;
use App\Services\UserServices;
use Illuminate\Http\Request;

class FarmersController extends BaseAPIController
{
    protected UserServices $userServices;
    public function __construct(UserServices $userServices)
    {
        $this->userServices = $userServices;
    }
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $farmers = $this->userServices->getListUser($request, UserTypes::FARMER);
        return $this->successResponse($farmers);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $farmers = $this->userServices->getUserDetail($id);
        return $this->successResponse($farmers);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {

    }

    /**
     * Update the specified resource in storage.
     */
    public function update(string $id, UpdateFarmerFormRequest $request)
    {
        $farmer = $this->userServices->updateUser($request, $id);
        return $this->successResponse($farmer);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $res = $this->userServices->deleteUser($id);
        return $res ? $this->successResponse() : $this->errorResponse('ไม่พบข้อมูลผู้ใช้งาน');
    }

    public function getImage(Request $request)
    {
        $image = new ImageHelper();
        return $image->getImage($request->get('path'));
    }

    public function checkMaxPackage($id)
    {
        return $this->successResponse($this->userServices->checkMaxPackage($id));
    }
}
