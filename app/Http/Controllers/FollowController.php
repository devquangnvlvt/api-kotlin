<?php

namespace App\Http\Controllers;

use App\Services\FollowService;
use Illuminate\Http\Request;

class FollowController extends Controller
{
   public function __construct(private FollowService $followService) {}


   public function index() {}

   public function follow(Request $request)
   {
      $validated = $request->validate([
         "following_id" => "required|int|exists:users,id"
      ]);
      $result = $this->followService->follow($request->user(), $validated['following_id']);
      return response()->json($result, 200);
   }

   public function unfollow(Request $request)
   {
      $validated = $request->validate([
         "following_id" => "required|int|exists:users,id"
      ]);
      $result = $this->followService->follow($request->user(), $validated['following_id']);
      return response()->json($result, 200);
   }

   public function isFollowing(Request $request)
   {
      $validated = $request->validate([
         "following_id" => "required|int|exists:users,id"
      ]);
      $result = $this->followService->isFollowing($request->user(), $validated['following_id']);
      return response()->json($result, 200);
   }

   public function getFollowers(Request $request)
   {
      $userId = $request->user()->id;
      $result = $this->followService->getFollowers($userId);
      return response()->json($result, 200);
   }

   public function getFollowing(Request $request)
   {
      $userId = $request->user()->id;
      $result = $this->followService->getFollowing($userId);
      return response()->json($result, 200);
   }
}
