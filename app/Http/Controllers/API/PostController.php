<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Post;
use Illuminate\Http\Request;


class PostController extends Controller
{
    public function index()
    {
        $posts = Post::with(['tags', 'category'])->orderByDesc('id')->paginate(6);

        return $posts;
    }
}
