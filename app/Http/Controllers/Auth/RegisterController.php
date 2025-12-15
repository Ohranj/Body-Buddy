<?php

namespace App\Http\Controllers\Auth;

use App\Http\Actions\CalorieTargets\CreateCalorieTarget;
use Carbon\Carbon;
use App\Models\User;
use Inertia\Inertia;
use Illuminate\Http\Request;
use App\Traits\JsonResponseTrait;
use App\Http\Actions\Logs\InsertLog;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\RegisterRequest;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\JsonResponse;

class RegisterController extends Controller
{

    use JsonResponseTrait;

    /**
     * Handle the incoming request.
     */
    public function index(Request $request)
    {
        return Inertia::render('Register');
    }

    /**
     * 
     */
    public function store(RegisterRequest $request, InsertLog $insertLog, CreateCalorieTarget $createCalorieTarget): JsonResponse
    {
        $params = collect($request->all())->only(['forename', 'surname', 'email']);
        $user = User::create([...$params, ...['password' => $request->password_hashed]]);

        $insertLog->execute($user, 'REGISTERED');

        Auth::loginUsingId($user->id);
        $request->session()->regenerate();
        $insertLog->execute($user, 'LOGGED_IN');

        $date = Carbon::createFromTimestamp(0);
        $key = $createCalorieTarget->execute(user: $user->id, target: 2500, take_effect: $date);
        Log::info($key);
        $insertLog->execute(model: $user, activity: 'NEW_CALORIE_TARGET');

        return $this->sendJsonResponse(true, 'Account created', [], [], 201);
    }
}
