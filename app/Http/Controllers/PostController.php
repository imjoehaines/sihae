<?php

namespace Sihae\Http\Controllers;

use Illuminate\Http\Request;

use View;
use Sihae\Post;
use Sihae\Http\Requests;
use Sihae\Http\Controllers\Controller;

class PostController extends Controller
{
    /**
     * Display the specified resource.
     *
     * @param string $slug
     * @return Response
     */
    public function show($slug)
    {
        $post = Post::findBySlugOrFail($slug);

        return View::make('post', [
            'title' => $post->title,
            'body' => $post->body,
        ]);
    }
}
