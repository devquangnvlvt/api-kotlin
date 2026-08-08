<?php

namespace App\Http\Controllers;

use App\Models\DailyTask;
use App\Services\DailyTaskService;

class DailyTaskController extends Controller
{
    public function __construct(private DailyTaskService $dailyTaskService) {}


    /**
     * Lấy ra toàn bộ task có is_active = 1;
     */
    public function index()
    {
        return $this->dailyTaskService->getDailyTasks();
    }
    
    /**
     * Cập nhật trạng thái nhiệm vụ
     */
    public function updateStatus(DailyTask $id)
    {
        return $this->dailyTaskService->updateStatus($id->id);
    }
}
