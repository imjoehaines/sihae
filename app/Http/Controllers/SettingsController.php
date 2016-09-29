<?php

namespace Sihae\Http\Controllers;

use View;
use Input;
use Redirect;
use Sihae\BlogConfig;
use Sihae\Http\Requests\SettingsRequest;

class SettingsController extends Controller
{
    /**
     * Display the settings page
     *
     * @return Response
     */
    public function display() : \Illuminate\View\View
    {
        $title = Input::old('title') ?: BlogConfig::title();
        $postsPerPage = Input::old('postsPerPage') ?: BlogConfig::postsPerPage();
        $showLoginLink = BlogConfig::showLoginLink();
        $summary = Input::old('summary') ?: BlogConfig::summary();

        return View::make('settings', [
            'title' => $title,
            'postsPerPage' => $postsPerPage,
            'showLoginLink' => $showLoginLink,
            'summary' => $summary,
        ]);
    }

    /**
     * Update the BlogConfig with the new settings
     *
     * @param SettingsRequest $request
     * @return Redirect SettingsController@display
     */
    public function store(SettingsRequest $request) : \Illuminate\Http\RedirectResponse
    {
        BlogConfig::set('showLoginLink', isset($request->showLoginLink));
        BlogConfig::setAll($request->all());

        $this->flashMessage($request, 'Successfully updated your settings!', 'success');

        return Redirect::action('SettingsController@display');
    }
}
