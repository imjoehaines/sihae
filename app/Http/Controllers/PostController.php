<?php

namespace Sihae\Http\Controllers;

use Illuminate\Http\Request;

use View;
use Markdown;
use Purifier;
use Redirect;
use Sihae\Post;
use Sihae\Http\Requests;
use Sihae\Http\Controllers\Controller;
use Sihae\Http\Requests\NewPostRequest;

class PostController extends Controller
{
    /**
     * Display a post by slug
     *
     * @param string $slug
     * @return Response
     */
    public function show($slug)
    {
        $post = Post::findBySlugOrFail($slug);

        return View::make('post', [
            'title' => $post->title,
            'body' => Purifier::clean(Markdown::string($post->body)),
        ]);
    }

    /**
     * Displays the form to create a new post
     *
     * @return Response
     */
    public function create()
    {
        return View::make('createpost');
    }

    /**
     * Store a new post
     *
     * @param NewPostRequest $request
     * @return Response redirect to the new post's page
     */
    public function store(NewPostRequest $request)
    {
        $post = new Post;

        $post->title = $request->get('title');
        $post->summary = $request->get('summary');
        $post->body = $request->get('body');
        $post->save();

        return Redirect::action('PostController@show', [$post->slug]);
    }
}
