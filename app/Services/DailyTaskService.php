<?php

namespace App\Services;

use App\Traits\ApiResponser;
use App\Models\User;
use App\Models\DailyTask;

class DailyTaskService
{
    use ApiResponser;

    public function getDailyTasks()
    {
        return $this->success(DailyTask::active()->get(), 200);
    }

    public function updateStatus(int $id)
    {
        $dailyTask = DailyTask::find($id);
        if (!$dailyTask) {
            return $this->error('Không tìm thấy nhiệm vụ', 404);
        }
        $dailyTask->is_active = $dailyTask->is_active  == 1 ? 0 : 1;
        $dailyTask->save();

        return $this->success($dailyTask, 200);
    }
}
