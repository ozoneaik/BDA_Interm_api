<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Services\UserServices;
use Illuminate\Http\Request;

class UsersController extends BaseAPIController
{
    protected UserServices $userService;
    public function __construct(UserServices $userServices)
    {
        $this->userService = $userServices;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $userType = $request->get('type');
        return $this->successResponse($this->userService->getListUser($request, $userType));
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $farmers = $this->userService->getUserDetail($id);
        return $this->successResponse($farmers);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $res = $this->userService->deleteUser($id);
        return $res ? $this->successResponse() : $this->errorResponse('ไม่พบข้อมูลผู้ใช้งาน');
    }

    public function approveOrRejectUser($id, Request $request)
    {
        $res = $this->userService->approveOrReject($id, $request);
        return $res ? $this->successResponse() : $this->errorResponse('ไม่พบข้อมูลผู้ใช้งาน');
    }
}
