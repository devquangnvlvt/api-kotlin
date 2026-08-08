<?php

namespace App\Services;

use App\Models\UserDailyTask;
use App\Traits\ApiResponser;

class UserDailyTaskService
{
    use ApiResponser;

    public function getUserDailyTasks($userId)
    {

        return $this->success(UserDailyTask::where('user_id', $userId)->get(), 200);
    }
}
