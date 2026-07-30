<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Post;
use App\Models\Comment;
use App\Http\Resources\CommentResource;
use App\Services\CommentService;

class CommentController extends Controller
{
    public function __construct(private CommentService $commentService) {}

    /**
     * Danh sách comment của 1 bài viết
     */
    public function index(Request $request, Post $post)
    {
        $comments = $this->commentService->getPostComments(
            $post,
            $request->query('per_page', 20)
        );

        return CommentResource::collection($comments);
    }

    /**
     * Danh sách reply của 1 comment
     */
    public function replies(Request $request, Comment $comment)
    {
        $replies = $this->commentService->getReplies(
            $comment,
            $request->query('per_page', 20)
        );

        return CommentResource::collection($replies);
    }

    /**
     * Thêm comment mới
     */
    public function store(Request $request, Post $post)
    {
        $validated = $request->validate([
            'content'           => 'nullable|string|max:1000',
            'sticker_id'        => 'nullable|exists:stickers,id',
            'parent_comment_id' => 'nullable|exists:comments,id',
        ]);

        $comment = $this->commentService->createComment(
            $request->user(),
            $post,
            $validated
        );

        return response()->json([
            'comment' => new CommentResource($comment),
            'message' => 'Comment added successfully',
        ], 201);
    }

    /**
     * Xóa comment
     */
    public function destroy(Request $request, Comment $comment)
    {
        if ($comment->user_id !== $request->user()->id) {
            return response()->json(['message' => 'Forbidden'], 403);
        }

        $this->commentService->deleteComment($comment);

        return response()->json(['message' => 'Comment deleted successfully'], 200);
    }

    /**
     * Like / Unlike comment (toggle)
     */
    public function toggleLike(Request $request, Comment $comment)
    {
        $result = $this->commentService->toggleLike($request->user(), $comment);

        return response()->json($result, 200);
    }
}
