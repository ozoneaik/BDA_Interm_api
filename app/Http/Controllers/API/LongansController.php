<?php

namespace App\Http\Controllers\API;

use App\Helpers\ImageHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\CreateLonganRequest;
use App\Models\Farms;
use App\Services\FarmService;
use App\Services\LonganService;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;

class LongansController extends BaseAPIController
{
    protected LonganService $longanService;
    public function __construct(LonganService $longanService)
    {
        $this->longanService = $longanService;
    }

    public function index(Request $request){
        $farm_id = Arr::get($request, 'farm_id');
        $farm = $farm_id ? Farms::find($farm_id) : null;
        $longans = $this->longanService->getLonganList($request);
        $data = [
            'longans' => $longans,
            'farm' => $farm
        ];

        return $this->successResponse($data);
    }

    public function show($id) {
        $longan = $this->longanService->getLonganDetail($id);
        return $this->successResponse($longan);
    }

    /**
     * @throws \Exception
     */
    public function store(CreateLonganRequest $request)
    {
        $longan = $this->longanService->createOrUpdateLonganTreeInFarm(0, $request);
        if($longan){
            return $this->successResponse($longan);
        }else {
            return $this->errorResponse('over package');
        }
    }

    /**
     * @throws \Exception
     */
    public function update($id, CreateLonganRequest $request)
    {
        $longan = $this->longanService->createOrUpdateLonganTreeInFarm($id, $request);
        return $this->successResponse($longan);
    }

    public function getImage(Request $request){
        $image = new ImageHelper();
        return $image->getImage($request->get('path'));
    }
}
