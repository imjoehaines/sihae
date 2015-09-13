<?php

namespace Sihae\Http\Controllers;

use Illuminate\Http\Request;

use View;
use Sihae\Post;
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
        return View::make('landingpage', ['posts' => Post::all()]);
    }
}
