<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePostRequest;
use App\Http\Requests\UpdatePostRequest;
use App\Models\Post;
use Illuminate\Http\JsonResponse;

class PostController extends Controller
{
    public function index(): JsonResponse
    {
        $posts = Post::with('user')
            ->where('is_draft', false)
            ->whereNotNull('published_at')
            ->where('published_at', '<=', now())
            ->paginate(20);

        return response()->json($posts);
    }

    public function create(): string
    {
        return 'posts.create';
    }

    public function store(StorePostRequest $request): JsonResponse
    {
        $post = $request->user()
            ->posts()
            ->create($request->validated());

        return response()->json($post, 201);
    }

    public function show(Post $post): JsonResponse
    {
        abort_if($post->is_draft || $post->published_at > now(), 404);

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

    public function destroy(Post $post): JsonResponse
    {
        $this->authorize('delete', $post);
        $post->delete();

        return response()->json(null, 204);
    }
}
