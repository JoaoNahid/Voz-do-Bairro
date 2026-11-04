<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class LogoutTest extends TestCase
{
    use RefreshDatabase;

    public function test_authenticated_citizen_can_logout_and_session_is_invalidated(): void
    {
        $user = User::create([
            'name' => 'Luciana Ramos',
            'email' => 'luciana.ramos@exemplo.com',
            'password' => Hash::make('SenhaForte@2026!'),
            'remember_token' => 'teste_remember_token_123',
        ]);

        $this->actingAs($user);

        $response = $this->postJson('/api/auth/logout');

        $response->assertStatus(200)
            ->assertJsonPath('message', 'Sessão encerrada com sucesso.');

        $this->assertGuest();

        $user->refresh();
        $this->assertNull($user->remember_token);

        // Attempting to access auth profile should now return 401
        $protectedResponse = $this->getJson('/api/auth/me');
        $protectedResponse->assertStatus(401);
    }
}
