<?php

namespace Laravel\Dusk\Http\Controllers;

use Illuminate\Support\Facades\Auth;

class UserController
{
    /**
     * Retrieve the authenticated user identifier and class name.
     *
     * @param  string|null  $guard
     * @return array
     */
    public function user($guard = null)
    {
        $user = Auth::guard($guard)->user();

        if (! $user) {
            return [];
        }

        return [
            'id' => $user->getAuthIdentifier(),
            'className' => get_class($user),
        ];
    }

    /**
     * Login using the given user ID / email.
     *
     * @param  string  $userId
     * @param  string  $guard
     * @return Response
     */
    public function login($userId, $guard = null)
    {
        $guard    = $guard ?: config('auth.defaults.guard');
        $provider = config("auth.guards.{$guard}.provider");
        $model    = config("auth.providers.{$provider}.model");

        if (str_contains($userId, '@')) {
            $user = (new $model)->where('email', $userId)->first();
        } else {
            $user = (new $model)->find($userId);
        }

        Auth::guard($guard)->login($user);
    }

    /**
     * Log the user out of the application.
     *
     * @param  string  $guard
     * @return Response
     */
    public function logout($guard = null)
    {
        Auth::guard($guard ?: config('auth.defaults.guard'))->logout();
    }
}
