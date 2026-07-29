<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Post;
use App\Models\PostImage;
use App\Models\PostLike;
use App\Models\PostSave;
use App\Http\Resources\PostResource;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class PostController extends Controller
{
    /**
     * Feed - danh sách bài viết (mới nhất)
     */
    public function index(Request $request)
    {
        $posts = Post::with(['user', 'images'])
            ->where('status', 'published')
            ->orderByDesc('created_at')
            ->paginate($request->query('per_page', 20));

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
            'images.*' => 'url',
        ]);

        $post = Post::create([
            'user_id' => $request->user()->id,
            'caption' => $validated['caption'] ?? null,
            'status'  => 'published',
        ]);

        // Lưu ảnh nếu có
        if (!empty($validated['images'])) {
            $images = array_map(fn($url, $index) => [
                'post_id'    => $post->id,
                'image_url'  => $url,
                'sort_order' => $index,
            ], $validated['images'], array_keys($validated['images']));

            PostImage::insert($images);
        }

        $post->load(['user', 'images']);

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
        if ($post->status === 'deleted') {
            return response()->json(['message' => 'Post not found'], 404);
        }

        $post->load(['user', 'images']);

        return response()->json(['post' => new PostResource($post)], 200);
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
            'caption' => 'sometimes|nullable|string|max:2000',
            'status'  => 'sometimes|in:published,hidden',
        ]);

        $post->update($validated);
        $post->refresh()->load(['user', 'images']);

        return response()->json([
            'post'    => new PostResource($post),
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

        $post->delete();

        return response()->json(['message' => 'Post deleted successfully'], 200);
    }

    /**
     * Like / Unlike bài viết (toggle)
     */
    public function toggleLike(Request $request, Post $post)
    {
        $userId = $request->user()->id;

        $like = PostLike::where('post_id', $post->id)
            ->where('user_id', $userId)
            ->first();

        if ($like) {
            $like->delete();
            $post->decrement('likes_count');
            $liked = false;
        } else {
            PostLike::create(['post_id' => $post->id, 'user_id' => $userId]);
            $post->increment('likes_count');
            $liked = true;
        }

        return response()->json([
            'liked'       => $liked,
            'likes_count' => $post->fresh()->likes_count,
        ], 200);
    }

    /**
     * Save / Unsave bài viết (toggle)
     */
    public function toggleSave(Request $request, Post $post)
    {
        $userId = $request->user()->id;

        $save = PostSave::where('post_id', $post->id)
            ->where('user_id', $userId)
            ->first();

        if ($save) {
            $save->delete();
            $saved = false;
        } else {
            PostSave::create(['post_id' => $post->id, 'user_id' => $userId]);
            $saved = true;
        }

        return response()->json([
            'saved' => $saved,
        ], 200);
    }

    /**
     * Danh sách bài viết của 1 user cụ thể
     */
    public function userPosts(Request $request, int $userId)
    {
        $posts = Post::with(['user', 'images'])
            ->where('user_id', $userId)
            ->where('status', 'published')
            ->orderByDesc('created_at')
            ->paginate($request->query('per_page', 20));

        return PostResource::collection($posts);
    }
}
