<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePostRequest;
use App\Http\Requests\UpdatePostRequest;
use App\Models\Post;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;

class PostController extends Controller
{
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

    public function create(): string
    {
        return 'posts.create';
    }

    public function store(StorePostRequest $request): JsonResponse
    {
        $post = $request->user()->posts()->create($request->validated());

        return response()->json($post, 201);
    }

    public function show(Post $post): JsonResponse
    {
        abort_if(
            $post->is_draft || $post->published_at === null || $post->published_at->isFuture(),
            404
        );

        return response()->json($post->load('user'));
    }

    public function edit(Post $post): string
    {
        $this->authorize('update', $post);

        return 'posts.edit';
    }

    public function update(UpdatePostRequest $request, Post $post): JsonResponse
    {
        $this->authorize('update', $post);

        $post->update($request->validated());

        return response()->json($post);
    }

    public function destroy(Post $post): Response
    {
        $this->authorize('delete', $post);

        $post->delete();

        return response()->noContent();
    }
}
