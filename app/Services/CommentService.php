<?php

namespace App\Services;

use App\Models\Comment;
use App\Models\CommentLike;
use App\Models\Post;
use App\Models\User;
use App\Traits\ApiResponser;

class CommentService
{
    use ApiResponser;

    /**
     * Get root comments of a post
     */
    public function getPostComments(Post $post, int $perPage = 20)
    {
        return Comment::with(['user'])
            ->where('post_id', $post->id)
            ->whereNull('parent_comment_id')
            ->orderByDesc('created_at')
            ->paginate($perPage);
    }

    /**
     * Get replies to a parent comment
     */
    public function getReplies(Comment $comment, int $perPage = 20)
    {
        return Comment::with(['user'])
            ->where('parent_comment_id', $comment->id)
            ->orderBy('created_at')
            ->paginate($perPage);
    }

    /**
     * Store new comment and increment post comments_count
     */
    public function createComment(User $user, Post $post, array $validated): Comment
    {
        $comment = Comment::create([
            'post_id'           => $post->id,
            'user_id'           => $user->id,
            'content'           => $validated['content'] ?? null,
            'sticker_id'        => $validated['sticker_id'] ?? null,
            'parent_comment_id' => $validated['parent_comment_id'] ?? null,
        ]);

        $post->increment('comments_count');

        return $comment->load('user');
    }

    /**
     * Delete comment and decrement post comments_count
     */
    public function deleteComment(Comment $comment): void
    {
        $postId = $comment->post_id;
        $comment->delete();

        Post::where('id', $postId)->decrement('comments_count');
    }

    /**
     * Toggle Like / Unlike comment
     */
    public function toggleLike(User $user, Comment $comment): array
    {
        $like = CommentLike::where('comment_id', $comment->id)
            ->where('user_id', $user->id)
            ->first();

        if ($like) {
            $like->delete();
            $comment->decrement('likes_count');
            $liked = false;
        } else {
            CommentLike::create(['comment_id' => $comment->id, 'user_id' => $user->id]);
            $comment->increment('likes_count');
            $liked = true;
        }

        return [
            'liked'       => $liked,
            'likes_count' => $comment->fresh()->likes_count,
        ];
    }
}
