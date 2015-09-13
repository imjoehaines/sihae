<?php

namespace Sihae\Http\Controllers;

use Illuminate\Http\Request;

use View;
use Sihae\Post;
use Sihae\BlogConfig;
use Sihae\Http\Requests;
use Sihae\Http\Controllers\Controller;

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

        return View::make('landingpage', ['posts' => Post::paginate($perPage)]);
    }
}
