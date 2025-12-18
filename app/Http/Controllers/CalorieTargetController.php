<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use App\Traits\JsonResponseTrait;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use App\Http\Actions\Logs\InsertLog;
use Illuminate\Support\Facades\Auth;
use App\Http\Actions\CalorieTargets\CreateCalorieTarget;
use App\Http\Actions\CalorieTargets\DeleteCalorieTargetById;
use App\Http\Requests\CalorieTargets\CreateCalorieTargetRequest;

class CalorieTargetController extends Controller
{
    use JsonResponseTrait;

    /**
     * 
     */
    public function index(Request $request)
    {
        //Paginate
        $targets = DB::table('calorie_targets')->where('user_id', Auth::id())->orderBy('take_effect', 'asc')->get();
        foreach ($targets as $target) {
            $target->toggled = false;
            $target->human_take_effect = Carbon::parse($target->take_effect)->format('M jS Y');
            $target->human_created = Carbon::parse($target->created_at)->format('M jS Y');
        }
        $data = [
            'targets' => $targets
        ];
        return $this->sendJsonResponse(state: true, message: 'Calorie targets retrieved', errors: [], data: $data, status: 201);
    }

    /**
     * 
     */
    public function store(CreateCalorieTargetRequest $createCalorieTargetRequest, InsertLog $insertLog, CreateCalorieTarget $createCalorieTarget): JsonResponse
    {
        ['target' => $target, 'formattedDate' => $formattedDate] = $createCalorieTargetRequest->validated();
        $key = $createCalorieTarget->execute(user: Auth::id(), target: $target, take_effect: $formattedDate);
        $insertLog->execute(model: Auth::user(), activity: 'NEW_CALORIE_TARGET');
        return $this->sendJsonResponse(state: true, message: 'Calorie target updated', errors: [], data: [], status: 201);
    }

    /**
     * 
     */
    public function destroy(Request $request, int $id, DeleteCalorieTargetById $deleteCalorieTargetById)
    {
        $row = DB::table('calorie_targets')->where('user_id', Auth::id())->where('id', $id)->first();
        if (!$row->can_trash) {
            return $this->sendJsonResponse(state: false, message: 'Unable to trash default entry', errors: [], data: [], status: 422);
        }
        $rows = $deleteCalorieTargetById->execute(id: $id, userId: Auth::id());
        if ($rows == 0) {
            return $this->sendJsonResponse(state: false, message: 'Failed to trash entry', errors: [], data: [], status: 422);
        }
        return $this->sendJsonResponse(state: true, message: 'Entry trashed', errors: [], data: [], status: 201);
    }
}
