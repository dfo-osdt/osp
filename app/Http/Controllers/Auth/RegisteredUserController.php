<?php

namespace App\Http\Controllers\Auth;

use App\Actions\Accounts\RegisterUser;
use App\DTOs\RegisterUserData;
use App\Http\Controllers\Controller;
use App\Rules\AuthorizedEmailDomain;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rules\Password;

class RegisteredUserController extends Controller
{
    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'email' => ['bail', 'required', 'string', 'email', 'max:255', new AuthorizedEmailDomain],
            'password' => ['required', 'confirmed', Password::min(config('auth.password_min_length'))->uncompromised()],
            'locale' => ['string', 'max:2', 'in:en,fr'],
        ]);

        $data = new RegisterUserData(
            $validated['first_name'],
            $validated['last_name'],
            $validated['email'],
            $validated['password'],
            $validated['locale'] ?? app()->getLocale()
        );

        $user = RegisterUser::register($data);

        event(new Registered($user));

        return response()->json([
            'message' => 'registered',
            'email' => $user->email,
        ]);
    }
}
