<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Traits\JsonResponseTrait;
use App\Http\Actions\Logs\InsertLog;
use Illuminate\Support\Facades\Auth;
use App\Http\Actions\CalorieTargets\CreateCalorieTarget;
use App\Http\Requests\CalorieTargets\CreateCalorieTargetRequest;
use Illuminate\Http\JsonResponse;

class CalorieTargetController extends Controller
{
    use JsonResponseTrait;

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
}
