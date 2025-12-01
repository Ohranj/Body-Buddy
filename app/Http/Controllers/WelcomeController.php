<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Broadcast;
use Inertia\Inertia;

class WelcomeController extends Controller
{
    //
    public function __invoke()
    {
        Broadcast::on('test.1')->as('anon')->with(['surname' => 'Dorrington'])->send();
        return Inertia::render('Welcome');
    }
}
