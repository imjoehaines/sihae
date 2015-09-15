<?php

namespace Sihae\Http\Controllers;

use View;
use Markdown;
use Purifier;
use Sihae\Post;
use Sihae\BlogConfig;
use Sihae\Http\Requests;
use Illuminate\Http\Request;

class LandingPageController extends Controller
{
    /**
     * Display the landing page
     *
     * @return Response
     */
    public function display()
    {
        $perPage = BlogConfig::postsPerPage();
        $posts = Post::orderBy('created_at', 'desc')->paginate($perPage);

        foreach ($posts as $post) {
            $post->summary = Purifier::clean(Markdown::string($post->summary));
        }

        return View::make('landingpage', [
            'posts' => $posts,
        ]);
    }
}
