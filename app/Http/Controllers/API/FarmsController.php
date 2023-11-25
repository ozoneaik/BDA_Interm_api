<?php

namespace App\Http\Controllers\API;

use App\Helpers\ImageHelper;
use App\Http\Requests\CreateFarmRequest;
use App\Services\FarmService;
use App\Services\LonganService;
use Illuminate\Http\Request;

class FarmsController extends BaseAPIController
{
    protected FarmService $farmService;
    protected LonganService $longanService;
    public function __construct(FarmService $farmService,LonganService $longanService)
    {
        $this->farmService = $farmService;
        $this->longanService = $longanService;
    }
    public function index(Request $request)
    {
        $farms = $this->farmService->getListFarms($request);
        return $this->successResponse($farms);
    }

    public function show($id, Request $request)
    {
        $farms = $this->farmService->getListLonganByFarm($id, $request);
        return $this->successResponse($farms);
    }

    public function store(CreateFarmRequest $request)
    {
        $farm = $this->farmService->createOrUpdateFarms($request, 0);
        if($farm){
            return $this->successResponse($farm);
        }else {
            return $this->errorResponse('over package');
        }
    }

    public function update($id, Request $request)
    {
        $farm = $this->farmService->createOrUpdateFarms($request, $id);
        return $this->successResponse($farm);
    }

    public function getImage(Request $request)
    {
        $image = new ImageHelper();
        return $image->getImage($request->get('path'));
    }
}
