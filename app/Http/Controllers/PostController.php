<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Post;
use App\Http\Resources\PostResource;
use App\Services\PostService;

class PostController extends Controller
{
    public function __construct(private PostService $postService) {}

    /**
     * Feed - danh sách bài viết (mới nhất)
     */
    public function index(Request $request)
    {
        $posts = $this->postService->getFeed(
            $request->query('per_page', 20)
        );

        return PostResource::collection($posts);
    }

    /**
     * Tạo bài viết mới
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'caption'  => 'nullable|string|max:2000',
            'images'   => 'nullable|array|max:10',
            'images.*' => 'image|mimes:jpg,jpeg,png,webp|max:5120',
        ]);

        $post = $this->postService->createPost(
            $request->user(),
            $validated,
            $request->hasFile('images') ? $request->file('images') : null
        );

        return response()->json([
            'post'    => new PostResource($post),
            'message' => 'Post created successfully',
        ], 201);
    }

    /**
     * Chi tiết bài viết
     */
    public function show(Request $request, Post $post)
    {
        $postData = $this->postService->getPost($post);

        if (!$postData) {
            return response()->json(['message' => 'Post not found'], 404);
        }

        return response()->json(['post' => new PostResource($postData)], 200);
    }

    /**
     * Cập nhật bài viết
     */
    public function update(Request $request, Post $post)
    {
        if ($post->user_id !== $request->user()->id) {
            return response()->json(['message' => 'Forbidden'], 403);
        }

        $validated = $request->validate([
            'caption' => 'sometimes|nullable|string|max:5000',
            'status'  => 'sometimes|in:published,hidden,followers_only',
        ]);

        $updatedPost = $this->postService->updatePost($post, $validated);

        return response()->json([
            'post'    => new PostResource($updatedPost),
            'message' => 'Post updated successfully',
        ], 200);
    }

    /**
     * Xóa bài viết (soft delete)
     */
    public function destroy(Request $request, Post $post)
    {
        if ($post->user_id !== $request->user()->id) {
            return response()->json(['message' => 'Forbidden'], 403);
        }

        $this->postService->deletePost($post);

        return response()->json(['message' => 'Post deleted successfully'], 200);
    }

    /**
     * Like / Unlike bài viết (toggle)
     */
    public function toggleLike(Request $request, Post $post)
    {
        $result = $this->postService->toggleLike($request->user(), $post);

        return response()->json($result, 200);
    }

    /**
     * Save / Unsave bài viết (toggle)
     */
    public function toggleSave(Request $request, Post $post)
    {
        $result = $this->postService->toggleSave($request->user(), $post);

        return response()->json($result, 200);
    }

    /**
     * Danh sách bài viết của 1 user cụ thể
     */
    public function userPosts(Request $request, int $userId)
    {
        $posts = $this->postService->getUserPosts(
            $userId,
            $request->query('per_page', 20)
        );

        return PostResource::collection($posts);
    }
}
