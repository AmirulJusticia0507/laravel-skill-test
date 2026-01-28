<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePostRequest;
use App\Http\Requests\UpdatePostRequest;
use App\Models\Post;
use Illuminate\Http\JsonResponse;

class PostController extends Controller
{
    /**
     * GET /posts
     */
    public function index(): JsonResponse
    {
        $posts = Post::with('user')
            ->where('is_draft', false)
            ->whereNotNull('published_at')
            ->where('published_at', '<=', now())
            ->paginate(20);

        return response()->json([
            'data' => $posts->items(),
            'meta' => [
                'current_page' => $posts->currentPage(),
                'last_page' => $posts->lastPage(),
                'per_page' => $posts->perPage(),
                'total' => $posts->total(),
            ],
        ]);
    }

    /**
     * GET /posts/create
     */
    public function create(): string
    {
        return 'posts.create';
    }

    /**
     * POST /posts
     */
    public function store(StorePostRequest $request): JsonResponse
    {
        $post = $request->user()
            ->posts()
            ->create($request->validated());

        return response()->json($post, 201);
    }

    /**
     * GET /posts/{post}
     */
    public function show(Post $post): JsonResponse
    {
        abort_if(
            $post->is_draft ||
            ($post->published_at && $post->published_at->isFuture()),
            404
        );

        return response()->json($post->load('user'));
    }

    /**
     * GET /posts/{post}/edit
     */
    public function edit(Post $post): string
    {
        $this->authorize('update', $post);

        return 'posts.edit';
    }

    /**
     * PUT/PATCH /posts/{post}
     */
    public function update(UpdatePostRequest $request, Post $post): JsonResponse
    {
        $post->update($request->validated());

        return response()->json($post);
    }

    /**
     * DELETE /posts/{post}
     */
    public function destroy(Post $post): JsonResponse
    {
        $this->authorize('delete', $post);

        $post->delete();

        return response()->json(null, 204);
    }
}
