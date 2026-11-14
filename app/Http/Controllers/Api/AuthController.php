<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Laravel\Sanctum\PersonalAccessToken;

class AuthController extends Controller
{
    private const PASSWORD_BYTES_RULE = 'regex:/\A[^\x00]{1,72}\z/';

    public function register(Request $request): JsonResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'password' => [
                'bail',
                'required',
                'string',
                'min:8',
                self::PASSWORD_BYTES_RULE,
                'confirmed',
            ],
        ], [
            'password.confirmed' => 'Паролі не збігаються.',
            'password.regex' => 'Пароль має містити не більше 72 байтів і не може містити нульовий байт.',
        ]);

        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
        ]);

        return response()->json([
            'data' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
            ],
        ], 201);
    }

    public function login(Request $request): JsonResponse
    {
        $credentials = $request->validate([
            'email' => ['required', 'email', 'max:255'],
            'password' => ['bail', 'required', 'string', self::PASSWORD_BYTES_RULE],
        ], [
            'password.regex' => 'Пароль має містити не більше 72 байтів і не може містити нульовий байт.',
        ]);

        $guard = Auth::guard('web');

        if (! $guard->once($credentials)) {
            throw ValidationException::withMessages([
                'email' => ['Неправильна електронна пошта або пароль.'],
            ]);
        }

        $user = $guard->user();
        $expiresAt = now()->addDay();


        if (! ($user instanceof User)) {
            return response()->json([
                'message' => 'Не вдалося визначити користувача.',
            ], 401);
        }

        $expiresAt = now()->addDay();
        $token = $user->createToken('bike-parking-api', ['*'], $expiresAt);

        return response()->json([
            'token_type' => 'Bearer',
            'access_token' => $token->plainTextToken,
            'expires_at' => $expiresAt->toIso8601String(),
        ])->header('Cache-Control', 'no-store');
    }

    public function logout(Request $request): Response
    {
        $token = $request->user()->currentAccessToken();

        abort_unless($token instanceof PersonalAccessToken, 401, 'Потрібен API-токен.');

        $token->delete();

        return response()->noContent();
    }
}
