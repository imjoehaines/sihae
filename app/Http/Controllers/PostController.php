<?php

namespace Sihae\Http\Controllers;

use View;
use Markdown;
use Purifier;
use Redirect;
use Sihae\Post;
use Stringy\Stringy;
use Sihae\Http\Requests;
use Illuminate\Http\Request;
use Sihae\Http\Controllers\Controller;
use Sihae\Http\Requests\NewPostRequest;

class PostController extends Controller
{
    /**
     * Display a post by slug
     *
     * @param string $slug
     * @return \Illuminate\View\View
     */
    public function show(string $slug) : \Illuminate\View\View
    {
        $post = Post::findBySlugOrFail($slug);

        return View::make('post', [
            'title' => $post->title,
            'body' => Purifier::clean(Markdown::string($post->body)),
            'slug' => $post->slug,
        ]);
    }

    /**
     * Displays the form to create a new post
     *
     * @return \Illuminate\View\View
     */
    public function create() : \Illuminate\View\View
    {
        return View::make('createpost');
    }

    /**
     * Store a new post
     *
     * @param NewPostRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(NewPostRequest $request) : \Illuminate\Http\RedirectResponse
    {
        $post = new Post;

        $summary = Stringy::create($request->body)->safeTruncate(450, '…');

        $post->title = $request->get('title');
        $post->summary = $summary;
        $post->body = $request->get('body');
        $post->save();

        $this->flashMessage($request, 'Successfully created your new post!', 'success');

        return Redirect::action('PostController@show', [$post->slug]);
    }

    /**
     * Displays the form to edit a post
     *
     * @param string $slug
     * @return \Illuminate\View\View
     */
    public function edit(string $slug) : \Illuminate\View\View
    {
        $post = Post::findBySlugOrFail($slug);

        return View::make('editpost', [
            'title' => $post->title,
            'body' => $post->body,
            'slug' => $post->slug,
        ]);
    }

    /**
     * Updates a post
     *
     * @param NewPostRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(NewPostRequest $request) : \Illuminate\Http\RedirectResponse
    {
        $post = Post::findBySlugOrFail($request->slug);

        $summary = Stringy::create($request->body)->safeTruncate(450, '…');

        $post->title = $request->get('title');
        $post->summary = $summary;
        $post->body = $request->get('body');
        $post->save();

        $this->flashMessage($request, 'Successfully edited your post!', 'success');

        return Redirect::action('PostController@show', [$post->slug]);
    }
}
