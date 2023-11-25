<?php

namespace App\Http\Controllers\API;

use App\Helpers\ImageHelper;
use App\Models\WebApplication;
use App\Http\Requests\WebApplicationRequest;
use Illuminate\Http\Request;
use App\Services\ApplicationService;

class WebApplicationController extends BaseAPIController
{
    protected ApplicationService $applicationService;
    public function __construct(ApplicationService $userServices)
    {
        $this->applicationService = $userServices;
    }
    public function index(WebApplicationRequest $request)
    {
        $app = $this->applicationService->index($request);
        return $app ? $this->successResponse() : $this->errorResponse();
    }

    public function getApps(){
        return WebApplication::all();
    }

    public function getImage(Request $request){
        $image = new ImageHelper();
        return $image->getImage($request->get('path'));
    }

    public function appDetail(string $id){
        $app = $this->applicationService->getAppDetail($id);
        return $this->successResponse($app);
    }


    public function editApp(string $id, WebApplicationRequest $request){
        $app = $this->applicationService->EditApp($id, $request);
        return $app ? $this->successResponse() : $this->errorResponse('ไม่พบข้อมูลแอปพลิเคชัน');
    }


    public function changeStatusApp(string $id,Request $request){
        $app = $this->applicationService->updateStatus($id, $request);
        return $app ? $this->successResponse() : $this->errorResponse('ไม่พบข้อมูลแอปพลิเคชัน');
    }

    public function destroy(string $id){
        $res = $this->applicationService->deleteApp($id);
        return $res ? $this->successResponse() : $this->errorResponse('ไม่พบข้อมูลแอปพลิเคชัน');
    }

}
