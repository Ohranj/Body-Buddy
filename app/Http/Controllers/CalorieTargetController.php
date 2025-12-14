<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use App\Traits\JsonResponseTrait;
use App\Http\Actions\Logs\InsertLog;
use Illuminate\Support\Facades\Auth;
use App\Http\Actions\CalorieTargets\CreateCalorieTarget;
use Illuminate\Http\JsonResponse;

class CalorieTargetController extends Controller
{
    use JsonResponseTrait;

    /**
     * 
     */
    public function store(Request $request, InsertLog $insertLog, CreateCalorieTarget $createCalorieTarget): JsonResponse
    {
        $target = $request->target;
        $key = $createCalorieTarget->execute($target, Carbon::today());
        $insertLog->execute(Auth::user(), 'NEW_CALORIE_TARGET');
        return $this->sendJsonResponse(true, 'Calorie target updated', [], [], 201);
    }
}
