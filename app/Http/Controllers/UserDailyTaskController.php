<?php

namespace App\Http\Controllers;

use App\Services\UserDailyTaskService;
use Illuminate\Http\Request;

class UserDailyTaskController extends Controller
{
    public function __construct(private UserDailyTaskService $userDailyTaskService) {}

    public function index(Request $request)
    {
        return response()->json($this->userDailyTaskService->getUserDailyTasks($request->user()->id));
    }
}
