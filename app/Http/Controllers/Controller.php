<?php

namespace Sihae\Http\Controllers;

use Sihae\Http\Requests\Request;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

abstract class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    /**
     * Creates a new flash message
     *
     * @param Request $request
     * @param string $message
     * @param string $type
     */
    protected function flashMessage(Request $request, $message, $type = 'info')
    {
        $request->session()->flash('flash-message', $message);
        $request->session()->flash('flash-message-type', $type);
    }
}
