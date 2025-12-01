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
            return $this->sendJsonResponse(false, 'Unable to authenticate credentials', [], [], 422);
        }

        $credentials = $validator->safe(['email', 'password']);
        $isValidAttempt = Auth::attempt($credentials);
        if (!$isValidAttempt) {
            return $this->sendJsonResponse(false, 'Unable to authenticate credentials', [], [], 422);
        }

        $request->session()->regenerate();

        $user = Auth::user();
        $insertLog->execute($user, 'LOGGED_IN');

        return $this->sendJsonResponse(true, 'Account Authenticated', [], [], 200);
    }

    /**
     * 
     */
    public function logout(Request $request): RedirectResponse
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    }
}
