<?php

namespace App\Http\Controllers\API;

use App\Services\NotificationService;
use Illuminate\Http\Request;

class NotificationController extends BaseAPIController
{
    protected NotificationService $notificationService;
    public function __construct(NotificationService $notificationService)
    {
        $this->notificationService = $notificationService;
    }

    public function index(Request $request)
    {
        $data = $this->notificationService->getListNotification($request);
        return $data ? $this->successResponse($data) : $this->errorResponse();
    }

    public function show($id)
    {
        $data = $this->notificationService->getNotificationDetail($id);
        return $data ? $this->successResponse($data) : $this->errorResponse();
    }

    public function countNotifications()
    {
        $count = $this->notificationService->countNotifications();
        return $this->successResponse(['count' => $count]);
    }
}
