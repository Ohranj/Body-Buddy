<?php

namespace App\Http\Controllers;

use App\Http\Actions\Calories\RetrieveCaloriesByDay;
use App\Http\Actions\CalorieTargets\RetrieveLatestCalorieTarget;
use Inertia\Inertia;
use Inertia\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function __invoke(Request $request, RetrieveCaloriesByDay $retrieveCaloriesByDay, RetrieveLatestCalorieTarget $retrieveLatestCalorieTarget): Response
    {
        $today = Carbon::today();

        $calories = $retrieveCaloriesByDay->execute($today);
        $calorieTarget = $retrieveLatestCalorieTarget->execute();
        $totalCalories = $calories->reduce(fn($acc, $cur) => $acc += $cur->amount, 0);
        $totalCaloriesPercent = round(($totalCalories / $calorieTarget->target) * 100, 1);
        $caloriesRemaining = $calorieTarget->target - $totalCalories;

        return Inertia::render('Dashboard', [
            'user' => Auth::user(),
            'calories' => [
                'entries' => $calories,
                'total' => $totalCalories,
                'percent' => $totalCaloriesPercent,
                'dailyTarget' => [
                    'amount' => $calorieTarget->target,
                    'human_created' => Carbon::parse($calorieTarget->created_at)->format('M jS Y'),
                    'human_created_text' => Carbon::parse($calorieTarget->created_at)->diffForHumans()
                ],
                'remaining' => $caloriesRemaining
            ],
            'date' => [
                'human_day' => $today->format('l jS F Y'),
                'timestamp' => $today->timestamp
            ]
        ]);
    }
}


/**
 * Maybe move calories to a service class
 * Check null divide for percent calc
 */
