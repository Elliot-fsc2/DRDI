<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class FallbackRouteController extends Controller
{
    public function __invoke(Request $request)
    {
        return response()->view('errors.404', [], 404);
    }
}
