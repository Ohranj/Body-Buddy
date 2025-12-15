<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Inertia\Inertia;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Actions\CalorieTargets\RetrieveCalorieTargetForDay;

class ProfileController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request, RetrieveCalorieTargetForDay $retrieveCalorieTargetForDay)
    {
        $calorieTarget = $retrieveCalorieTargetForDay->execute(user: Auth::id(), day: $request->day->today());
        $calorieTarget->human_created = Carbon::parse($calorieTarget->created_at)->format('M jS Y');

        $date = Carbon::today()->toDateString();

        return Inertia::render('Profile', [
            'user' => Auth::user(),
            'date' => $date,
            'calorieTarget' => $calorieTarget
        ]);
    }
}
