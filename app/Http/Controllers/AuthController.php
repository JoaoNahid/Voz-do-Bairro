<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\LoginUserRequest;
use App\Http\Requests\RegisterUserRequest;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    /**
     * Register a new citizen user with a polymorphic address.
     */
    public function register(RegisterUserRequest $request): JsonResponse
    {
        $validated = $request->validated();

        $user = DB::transaction(function () use ($validated) {
            $user = User::create([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'password' => Hash::make($validated['password']),
            ]);

            $user->address()->create([
                'cep' => $validated['cep'],
                'logradouro' => $validated['logradouro'],
                'numero' => $validated['numero'],
                'complemento' => $validated['complemento'] ?? null,
                'bairro' => $validated['bairro'],
                'cidade' => $validated['cidade'],
                'uf' => $validated['uf'],
            ]);

            return $user;
        });

        // Log the user in immediately
        Auth::login($user, remember: true);
        $request->session()->regenerate();

        $user->load('address');

        return response()->json([
            'message' => 'Cadastro realizado com sucesso!',
            'user' => $this->formatUserData($user),
        ], 201);
    }

    /**
     * Authenticate citizen user with credentials and optional Remember Me.
     */
    public function login(LoginUserRequest $request): JsonResponse
    {
        $throttleKey = Str::transliterate(Str::lower($request->input('email')) . '|' . $request->ip());

        if (RateLimiter::tooManyAttempts($throttleKey, 5)) {
            $seconds = RateLimiter::availableIn($throttleKey);

            return response()->json([
                'message' => "Muitas tentativas incorretas. Tente novamente em {$seconds} segundos.",
            ], 429);
        }

        $credentials = $request->only('email', 'password');
        $remember = (bool) $request->input('remember', false);

        if (! Auth::attempt($credentials, $remember)) {
            RateLimiter::hit($throttleKey, 60);

            return response()->json([
                'message' => 'Credenciais inválidas. Verifique seu e-mail e senha.',
            ], 401);
        }

        RateLimiter::clear($throttleKey);
        $request->session()->regenerate();

        /** @var User $user */
        $user = Auth::user();
        $user->load('address');

        return response()->json([
            'message' => 'Login realizado com sucesso!',
            'user' => $this->formatUserData($user),
        ]);
    }

    /**
     * Return authenticated user profile and address.
     */
    public function me(Request $request): JsonResponse
    {
        $user = $request->user();

        if (! $user) {
            return response()->json(['message' => 'Não autenticado.'], 401);
        }

        $user->load('address');

        return response()->json([
            'user' => $this->formatUserData($user),
        ]);
    }

    /**
     * Terminate active user session and revoke remember token.
     */
    public function logout(Request $request): JsonResponse
    {
        $user = $request->user();

        Auth::guard('web')->logout();

        if ($user) {
            $user->forceFill(['remember_token' => null])->save();
        }

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return response()->json([
            'message' => 'Sessão encerrada com sucesso.',
        ]);
    }

    /**
     * Format user data structure for consistent API responses.
     */
    private function formatUserData(User $user): array
    {
        return [
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'address' => $user->address ? [
                'id' => $user->address->id,
                'cep' => $user->address->cep,
                'logradouro' => $user->address->logradouro,
                'numero' => $user->address->numero,
                'complemento' => $user->address->complemento,
                'bairro' => $user->address->bairro,
                'cidade' => $user->address->cidade,
                'uf' => $user->address->uf,
                'is_pilot_region' => $user->address->isPilotRegion(),
            ] : null,
        ];
    }
}
