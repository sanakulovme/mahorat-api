<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Post;

class PostController extends Controller
{
    // View all posts
    public function viewAll()
    {
        $posts = Post::with('user')->get();
        
        return response()->json([
            'ok' => true,
            'data' => $posts
        ], 200);
    }

    // View single post
    public function view($post_id)
    {
        $post = Post::with('user')->find($post_id);

        if (!$post) {
            return response()->json([
                'ok' => false,
                'message' => 'Post not found'
            ], 404);
        }

        return response()->json([
            'ok' => true,
            'data' => $post,
        ], 200);
    }

    // Create post
    public function create(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'body' => 'required|string',
            'user_id' => 'required|exists:users,id',
            'status' => 'required|string|in:draft,published,archived',
            'url' => 'required|string|max:255',
        ]);

        $post = Post::create($request->all());

        return response()->json([
            'ok' => true,
            'message' => 'Post created successfully',
            'data' => $post,
        ], 201);
    }

    // Update post
    public function update(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:posts,id',
            'title' => 'required|string|max:255',
            'body' => 'required|string',
            'user_id' => 'required|exists:users,id',
            'status' => 'required|string|in:draft,published,archived',
            'url' => 'required|string|max:255',
        ]);

        $post = Post::find($request->id);
        $post->update($request->except('id'));

        return response()->json([
            'ok' => true,
            'message' => 'Post updated successfully',
            'data' => $post,
        ], 200);
    }

    // Delete post
    public function delete($post_id)
    {
        $post = Post::find($post_id);

        if (!$post) {
            return response()->json([
                'ok' => false,
                'message' => 'Post not found'
            ], 404);
        }

        $post->delete();

        return response()->json([
            'ok' => true,
            'message' => 'Post deleted successfully',
        ], 200);
    }

    // Legacy method for backward compatibility
    public function showAll()
    {
        return $this->viewAll();
    }
}
