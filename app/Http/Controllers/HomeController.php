<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{

    /**
     * @param Request $request
     * @return Application|Factory|View|\Illuminate\Foundation\Application|RedirectResponse
     */
    public function index(Request $request): \Illuminate\Foundation\Application|View|Factory|RedirectResponse|Application
    {
        // If the user is not logged in, redirect to the login page.
        // this is the only endpoint so for that reason we can do this here, otherwise we would need to do this in the middleware
        if(session('user_token') == null) {
            return redirect()->route('login');
        }
        return view('home');
    }
}
