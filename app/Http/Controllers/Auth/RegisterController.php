<?php

namespace App\Http\Controllers\Auth;

use App\Http\Actions\Logs\InsertLog;
use Inertia\Inertia;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\RegisterRequest;
use App\Models\User;
use App\Traits\JsonResponseTrait;
use Illuminate\Support\Facades\Auth;
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
    public function store(RegisterRequest $request, InsertLog $insertLog): JsonResponse
    {
        $params = collect($request->all())->only(['forename', 'surname', 'email']);
        $user = User::create([...$params, ...['password' => $request->password_hashed]]);

        $insertLog->execute($user, 'REGISTERED');

        Auth::loginUsingId($user->id);
        $request->session()->regenerate();
        $insertLog->execute($user, 'LOGGED_IN');

        return $this->sendJsonResponse(true, 'Account created', [], [], 201);
    }
}
