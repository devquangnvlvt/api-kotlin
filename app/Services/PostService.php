<?php

namespace App\Services;

use App\Models\Post;
use App\Models\PostImage;
use App\Models\PostLike;
use App\Models\PostSave;
use App\Models\User;
use App\Traits\ApiResponser;

class PostService
{
    use ApiResponser;

    /**
     * Feed - Get latest published posts
     */
    public function getFeed(int $perPage = 20)
    {
        return Post::with(['user', 'images'])
            ->where('status', 'published')
            ->orderByDesc('created_at')
            ->paginate($perPage);
    }

    /**
     * Create new post
     */
    public function createPost(User $user, array $validated, $files = null): Post
    {
        $post = Post::create([
            'user_id' => $user->id,
            'caption' => $validated['caption'] ?? null,
            'status'  => 'published',
        ]);

        if ($files) {
            $images = [];
            foreach ($files as $index => $file) {
                $path = $file->store("posts/{$post->id}", 'public');
                $images[] = [
                    'post_id'    => $post->id,
                    'image_url'  => asset("storage/{$path}"),
                    'sort_order' => $index,
                ];
            }
            PostImage::insert($images);
        }
        $user->increment('posts_count');

        return $post->load(['user', 'images']);
    }

    /**
     * Get post details
     */
    public function getPost(Post $post): ?Post
    {
        if ($post->status === 'deleted') {
            return null;
        }

        return $post->load(['user', 'images']);
    }

    /**
     * Update existing post
     */
    public function updatePost(Post $post, array $validated): Post
    {
        $post->update($validated);
        return $post->refresh()->load(['user', 'images']);
    }

    /**
     * Delete post (soft delete)
     */
    public function deletePost(Post $post): bool
    {
        $post->user->decrement('posts_count');
        return (bool) $post->delete();
    }

    /**
     * Toggle Like / Unlike post
     */
    public function toggleLike(User $user, Post $post): array
    {
        $like = PostLike::where('post_id', $post->id)
            ->where('user_id', $user->id)
            ->first();

        if ($like) {
            $like->delete();
            $post->decrement('likes_count');
            $liked = false;
        } else {
            PostLike::create(['post_id' => $post->id, 'user_id' => $user->id]);
            $post->increment('likes_count');
            $liked = true;
        }

        return [
            'liked'       => $liked,
            'likes_count' => $post->fresh()->likes_count,
        ];
    }

    /**
     * Toggle Save / Unsave post
     */
    public function toggleSave(User $user, Post $post): array
    {
        $save = PostSave::where('post_id', $post->id)
            ->where('user_id', $user->id)
            ->first();

        if ($save) {
            $save->delete();
            $saved = false;
        } else {
            PostSave::create(['post_id' => $post->id, 'user_id' => $user->id]);
            $saved = true;
        }

        return [
            'saved' => $saved,
        ];
    }

    /**
     * Get posts authored by specific user
     */
    public function getUserPosts(int $userId, int $perPage = 20)
    {
        return Post::with(['user', 'images'])
            ->where('user_id', $userId)
            ->where('status', 'published')
            ->orderByDesc('created_at')
            ->paginate($perPage);
    }
}
