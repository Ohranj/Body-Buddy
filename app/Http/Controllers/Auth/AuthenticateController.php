<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use App\Traits\JsonResponseTrait;
use Illuminate\Http\JsonResponse;
use App\Http\Actions\Logs\InsertLog;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Validator;

class AuthenticateController extends Controller
{
    use JsonResponseTrait;

    /**
     * 
     */
    public function store(Request $request, InsertLog $insertLog): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if ($validator->fails()) {
            return $this->sendJsonResponse(state: false, message: 'Unable to authenticate credentials', errors: [], data: [], status: 422);
        }

        $credentials = $validator->safe(['email', 'password']);
        $isValidAttempt = Auth::attempt($credentials);
        if (!$isValidAttempt) {
            return $this->sendJsonResponse(state: false, message: 'Unable to authenticate credentials', errors: [], data: [], status: 422);
        }

        $insertLog->execute(model: Auth::user(), activity: 'LOGGED_IN');

        return $this->sendJsonResponse(state: true, message: 'Account Authenticated', errors: [], data: [], status: 200);
    }

    /**
     * 
     */
    public function logout(Request $request): RedirectResponse
    {
        Auth::logout();
        return redirect('/');
    }
}
