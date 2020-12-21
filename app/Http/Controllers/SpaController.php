<?php

namespace App\Http\Controllers;

/**
 * Class SpaController
 * @package App\Http\Controllers
 */
class SpaController extends Controller
{
    /**
     * Get the SPA view.
     *
     * @return \Illuminate\Http\Response
     */
    public function __invoke()
    {
        return view('spa');
    }
}
