<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class SessionController extends Controller
{
    private const PASSWORD_BYTES_RULE = 'regex:/\A[^\x00]{1,72}\z/';

    public function create(): View
    {
        return view('auth.login');
    }

    public function store(
        Request $request,
    ): RedirectResponse|Response {
        $credentials = $request->validate([
            'email' => [
                'required',
                'email',
                'max:255',
            ],

            'password' => [
                'bail',
                'required',
                'string',
                self::PASSWORD_BYTES_RULE,
            ],
        ], [
            'email.required' =>
            'Введіть електронну пошту.',

            'email.email' =>
            'Введіть коректну електронну пошту.',

            'password.required' =>
            'Введіть пароль.',

            'password.regex' =>
            'Пароль має містити не більше 72 байтів і не може містити нульовий байт.',
        ]);

        if (! Auth::guard('web')->attempt($credentials)) {
            $message = 'Неправильна електронна пошта або пароль.';

            if ($request->expectsJson()) {
                throw ValidationException::withMessages([
                    'email' => [$message],
                ]);
            }

            return back()
                ->withErrors(['email' => $message])
                ->onlyInput('email');
        }

        $request->session()->regenerate();

        if ($request->expectsJson()) {
            return response()->noContent();
        }

        return redirect()->intended(route('home'));
    }

    public function destroy(
        Request $request,
    ): RedirectResponse|Response {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        if ($request->expectsJson()) {
            return response()->noContent();
        }

        return redirect()->route('login');
    }
}
