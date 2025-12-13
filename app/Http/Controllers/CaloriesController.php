<?php

namespace App\Http\Controllers;

use App\Http\Actions\Calories\DeleteCalorieEntryById;
use Exception;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Services\CaloriesService;
use App\Traits\JsonResponseTrait;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Http\Actions\Calories\RetrieveCaloriesByDay;
use App\Http\Requests\Calories\CreateCaloriesRequest;
use App\Http\Actions\CalorieTargets\RetrieveCalorieTargetForDay;
use Illuminate\Http\JsonResponse;

class CaloriesController extends Controller
{
    use JsonResponseTrait;

    /**
     * 
     */
    public function index(Request $request, RetrieveCaloriesByDay $retrieveCaloriesByDay, RetrieveCalorieTargetForDay $retrieveCalorieTargetForDay): JsonResponse
    {
        $day = $request->day;

        $calories = $retrieveCaloriesByDay->execute($day);

        $runningTotal = 0;
        foreach ($calories as $entry) {
            $entry->human_date = Carbon::parse($entry->date)->setTimezone('Europe/London')->format('H:ia');
            $runningTotal += $entry->amount;
            $entry->running_total = $runningTotal;
            $entry->toggled = false;
        }

        $calorieTarget = $retrieveCalorieTargetForDay->execute($day);
        if ($calorieTarget == null) {
            throw new Exception('Target not found');
        }

        $caloriesService = new CaloriesService($calories, $calorieTarget->target);
        $caloriesService->tallyPerMeal()->sumCaloriesConsumed()->calculateCaloriesConsumedPercent()->sumRemainingCalories();

        $data = [
            'entries' => $calories,
            'total' => $caloriesService->consumed,
            'percent' => $caloriesService->percentConsumed,
            'mealTallies' => $caloriesService->mealTallies,
            'dailyTarget' => [
                'amount' => $calorieTarget->target,
                'human_created' => Carbon::parse($calorieTarget->updated_at)->format('M jS Y'),
                'human_created_text' => Carbon::parse($calorieTarget->updated_at)->diffForHumans()
            ],
            'remaining' => $caloriesService->remaining
        ];

        return $this->sendJsonResponse(true, 'Calories retrieved', [], $data, 200);
    }

    /**
     * 
     */
    public function store(CreateCaloriesRequest $request): JsonResponse
    {
        $created = Carbon::now();

        DB::table('calories')->insert([
            ...$request->validated(),
            'user_id' => Auth::id(),
            'created_at' => $created,
            'updated_at' => $created
        ]);

        return $this->sendJsonResponse(true, 'Calories assigned', [], [], 201);
    }

    /**
     * 
     */
    public function destroy(Request $request, int $id, DeleteCalorieEntryById $deleteCalorieEntryById)
    {
        $rows = $deleteCalorieEntryById->execute($id, Auth::id());
        if ($rows == 0) {
            return $this->sendJsonResponse(false, 'Failed to trash entry', [], [], 422);
        }
        return $this->sendJsonResponse(true, 'Entry trashed', [], [], 201);
    }
}
