<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use Inertia\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function __invoke(Request $request): Response
    {
        $day = $request->day;

        return Inertia::render('Dashboard', [
            'user' => Auth::user(),
            'date' => [
                'human_day' => $day->format('l jS F Y'),
                'timestamp' => $day->timestamp
            ]
        ]);
    }
}
