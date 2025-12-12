<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Carbon;
use Illuminate\Auth\Events\Login;
use Illuminate\Contracts\Auth\StatefulGuard;
use Illuminate\Contracts\Auth\Authenticatable;








// https://github.com/laravel/framework/blob/v12.40.2/src/Illuminate/Auth/SessionGuard.php






class CustomGuard implements StatefulGuard
{
    public const PREFIX = '79gq43';
    private $user = null;

    public function __construct(public $provider) {}

    /**
     * 
     */
    public function attempt(array $credentials = [], $remember = false)
    {
        $user = $this->provider->retrieveByCredentials($credentials);
        if (!$user) {
            return false;
        }

        $checkIsValid = $this->provider->validateCredentials($user, $credentials);
        if (!$checkIsValid) {
            return false;
        }

        $this->login($user);
        $this->setUser($user);
        return true;
    }

    public function once(array $credentials = [])
    {
        dd('once');
        throw new \Exception('Not implemented');
    }

    public function login(Authenticatable $user, $remember = false)
    {
        //  if ($remember) {
        //     $token = Str::random(12);
        //     $this->provider->updateRememberToken($user, $token);
        //     $value = ['token' => $token, 'id' => $user->id];
        //     Cookie::queue('pulse_ping_remember_me', json_encode($value), 60 * 24 * 30);
        // }
        $sessionExpiresAt = Carbon::now()->addMinutes((int)config('session.lifetime'));
        session([
            self::PREFIX . '_user_id' => $user->id,
            self::PREFIX . '_expires_at' => $sessionExpiresAt->getTimestampMs()
        ]);
        $guard = config('auth.defaults')['guard'];
        event(new Login(guard: $guard, user: $user, remember: $remember));
        session()->regenerate();
    }

    public function loginUsingId($id, $remember = false)
    {
        $sessionExpiresAt = Carbon::now()->addMinutes((int)config('session.lifetime'));
        session([
            self::PREFIX . '_user_id' => $id,
            self::PREFIX . '_expires_at' => $sessionExpiresAt->getTimestampMs()
        ]);
        $guard = config('auth.defaults')['guard'];
        $user = $this->user();
        event(new Login(guard: $guard, user: $user, remember: $remember));
        return $user;
    }

    public function onceUsingId($id)
    {
        dd('once using id');
        throw new \Exception('Not implemented');
    }

    public function viaRemember()
    {
        dd('via remember');
        throw new \Exception('Not implemented');
    }

    public function logout(): void
    {
        session()->invalidate();
        session()->regenerateToken();
        $this->user = null;
    }

    public function check()
    {
        //If already set on request then we don't need to fetch
        if ($this->hasUser()) {
            return true;
        }

        $id = session(self::PREFIX . '_user_id', false);
        if (!$id) {
            return false;
        }
        $user = $this->provider->retrieveById($id);
        if (!$user) {
            return false;
        }
        $this->setUser($user);
        return true;

        // $cookie = Cookie::get('pulse_ping_remember_me');
        // if ( ! $cookie) {
        //     return false;
        // }

        // $decodedCookie = json_decode($cookie);
        // $user = $this->provider->retrieveByToken($decodedCookie->id, $decodedCookie->token);
        // if ($user) {
        //     $this->login($user, true);
        //     session([self::PREFIX . '_via_remember_me' => true]);
        //     return true;
        // }

    }

    public function guest()
    {
        if ($this->check()) {
            return false;
        }
        return true;
    }

    public function user()
    {
        if (!$this->check()) {
            return null;
        }

        return $this->user;

        // $id = session(self::PREFIX . '_user_id', false);

        // if (! $id) {
        //     return null;
        // }

        // $user = $this->provider->retrieveById(identifier: $id);


        // if (! $user) {
        //     return null;
        // }

        // $this->user = $user;
        // return $this->user;
    }

    public function hasUser()
    {
        return boolval($this->user);
    }

    public function setUser(Authenticatable $user)
    {
        $this->user = $user;
    }

    public function id()
    {
        $exists = $this->check();
        if ($exists) {
            return $this->user->id;
        }
        return null;
    }

    public function validate(array $credentials = [])
    {
        dd('validate');
        throw new \Exception('Not implemented');
    }
}
