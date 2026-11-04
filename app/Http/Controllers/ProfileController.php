<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\DeleteAccountRequest;
use App\Http\Requests\UpdatePasswordRequest;
use App\Http\Requests\UpdateProfileRequest;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{
    /**
     * Update user profile and polymorphic address.
     */
    public function updateProfile(UpdateProfileRequest $request): JsonResponse
    {
        /** @var User $user */
        $user = $request->user();
        $validated = $request->validated();

        DB::transaction(function () use ($user, $validated) {
            $user->update([
                'name' => $validated['name'],
                'email' => $validated['email'],
            ]);

            $addressData = [
                'cep' => $validated['cep'],
                'logradouro' => $validated['logradouro'],
                'numero' => $validated['numero'],
                'complemento' => $validated['complemento'] ?? null,
                'bairro' => $validated['bairro'],
                'cidade' => $validated['cidade'],
                'uf' => $validated['uf'],
            ];

            if ($user->address) {
                $user->address->update($addressData);
            } else {
                $user->address()->create($addressData);
            }
        });

        $user->load('address');

        return response()->json([
            'message' => 'Dados cadastrais atualizados com sucesso!',
            'user' => [
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
            ],
        ]);
    }

    /**
     * Update user account password with high complexity policy.
     */
    public function updatePassword(UpdatePasswordRequest $request): JsonResponse
    {
        /** @var User $user */
        $user = $request->user();

        $user->forceFill([
            'password' => Hash::make($request->input('password')),
            'remember_token' => null,
        ])->save();

        return response()->json([
            'message' => 'Senha alterada com sucesso!',
        ]);
    }

    /**
     * Permanently delete user account and associated polymorphic address.
     */
    public function deleteAccount(DeleteAccountRequest $request): JsonResponse
    {
        /** @var User $user */
        $user = $request->user();

        DB::transaction(function () use ($user) {
            // Delete polymorphic address record
            $user->address()->delete();
            // Delete user account
            $user->delete();
        });

        Auth::guard('web')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return response()->json([
            'message' => 'Sua conta e seus dados foram excluídos definitivamente.',
        ]);
    }
}
