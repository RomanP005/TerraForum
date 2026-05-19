<?php

namespace App\Http\Controllers;

use Illuminate\View\View;

class AboutController extends Controller
{
    /**
     * Страница "О нас".
     */
    public function index(): View
    {
        return view('about');
    }
}
