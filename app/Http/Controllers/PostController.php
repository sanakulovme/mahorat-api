<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Post;

class PostController extends Controller
{
    public function showAll()
    {
        $data = Post::all();
        return response()->json([
            'ok' => true,
            'data' => $data
        ]);
    }
}
