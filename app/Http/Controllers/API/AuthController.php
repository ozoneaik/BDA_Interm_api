<?php

namespace App\Http\Controllers\API;

use App\Enums\UserApproveStatus;
use App\Http\Controllers\Controller;
use App\Http\Requests\LoginFormRequest;
use App\Http\Requests\RegisterFormRequest;
use App\Models\User;
use App\Services\UserServices;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Laravel\Sanctum\PersonalAccessToken;

class AuthController extends BaseAPIController
{

    protected UserServices $userServices;
    public function __construct(UserServices $userServices)
    {
        $this->userServices = $userServices;
    }

    public function login(LoginFormRequest $request)
    {
        $credentials = $request->only('email', 'password');
        if(auth()->validate($credentials)){
            $user = User::where('email', $credentials['email'])->first();
            if($user) {
                if($user->status !== UserApproveStatus::APPROVED->value) {
                    return $this->errorResponse("ผู้ใช้งานนี้ยังไม่ได้รับการอนุมัติจากระบบ กรุณาติดต่อผู้ดูแลระบบ", 401);
                }

                $token = User::generateToken($user);
                return $this->successResponse([
                    'token' => $token->plainTextToken,
                    'user' => $user
                ]);
            }else {
                return $this->errorResponse('ชื่อผู้ใช้หรือรหัสผ่านไม่ถูกต้อง', 401);
            }
        }else {
            return $this->errorResponse('ชื่อผู้ใช้หรือรหัสผ่านไม่ถูกต้อง', 401);
        }
    }

    public function loginWithLine(Request $request)
    {
        $res = $this->userServices->loginWithLine($request);
        $user = $res['user'];
        $is_register = $res['is_register'] ?? false;

        Log::info('loginWithLine', [
            'user', $user
        ]);
        if($is_register) {
            return $this->successResponse(['user' => $user]);
        }else {
            if($user->status !== UserApproveStatus::APPROVED->value) {
                return $this->errorResponse("ผู้ใช้งานนี้ยังไม่ได้รับการอนุมัติจากระบบ กรุณาติดต่อผู้ดูแลระบบ", 401);
            }else {
                $token = User::generateToken($user);
                if($user && $token) {
                    return $this->successResponse([
                        'token' => $token->plainTextToken,
                        'user' => $user
                    ]);
                }
            }
        }

        return $this->errorResponse('ชื่อผู้ใช้หรือรหัสผ่านไม่ถูกต้อง', 401);
    }

    public function register(RegisterFormRequest $request)
    {
        $user = $this->userServices->register($request);
        return $user ? $this->successResponse() : $this->errorResponse();
    }

    public function profile()
    {
        $user = $this->userServices->profile();
        return $this->successResponse($user);
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();
        return $this->successResponse();
    }
}
