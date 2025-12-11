<?php

namespace App\Http\Controllers;

use App\Http\Actions\Calories\RetrieveCaloriesByDay;
use App\Http\Actions\CalorieTargets\RetrieveCalorieTargetForDay;
use App\Services\CaloriesService;
use Exception;
use Inertia\Inertia;
use Inertia\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function __invoke(Request $request, RetrieveCaloriesByDay $retrieveCaloriesByDay, RetrieveCalorieTargetForDay $retrieveLatestCalorieTarget): Response
    {
        $today = Carbon::now();

        $calories = $retrieveCaloriesByDay->execute($today);
        $calorieTarget = $retrieveLatestCalorieTarget->execute($today);
        if ($calorieTarget == null) {
            throw new Exception('Target not found');
        }

        $caloriesService = new CaloriesService($calories, $calorieTarget->target);
        $caloriesService->sumCaloriesConsumed()->calculateCaloriesConsumedPercent()->sumRemainingCalories();

        return Inertia::render('Dashboard', [
            'user' => Auth::user(),
            'calories' => [
                'entries' => $calories,
                'total' => $caloriesService->consumed,
                'percent' => $caloriesService->percentConsumed,
                'dailyTarget' => [
                    'amount' => $calorieTarget->target,
                    'human_created' => Carbon::parse($calorieTarget->created_at)->format('M jS Y'),
                    'human_created_text' => Carbon::parse($calorieTarget->created_at)->diffForHumans()
                ],
                'remaining' => $caloriesService->remaining
            ],
            'date' => [
                'human_day' => $today->format('l jS F Y'),
                'timestamp' => $today->timestamp
            ]
        ]);
    }
}
