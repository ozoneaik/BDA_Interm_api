<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Services\FarmService;
use Illuminate\Http\Request;

class HomeController extends BaseAPIController
{
    public function home(FarmService $farmService)
    {
        $notifications = [];
        $columns = ['name', 'amount_of_rai', 'amount_of_tree', 'age_of_rai', 'id'];
        $relations = ['longans'];
        $farms = $farmService->getListFarms(null,$columns, $relations);
        $data = [
            'notifications' => $notifications,
            'farms' => $farms
        ];

        return $this->successResponse($data);
    }
}
