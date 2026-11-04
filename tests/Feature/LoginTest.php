<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\RateLimiter;
use Tests\TestCase;

class LoginTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        RateLimiter::clear('marcos.souza@exemplo.com|127.0.0.1');
    }

    public function test_citizen_can_login_with_valid_credentials(): void
    {
        $user = User::create([
            'name' => 'Marcos de Souza',
            'email' => 'marcos.souza@exemplo.com',
            'password' => Hash::make('SenhaForte@2026!'),
        ]);

        $user->address()->create([
            'cep' => '37550000',
            'logradouro' => 'Rua Principal',
            'numero' => '42',
            'bairro' => 'Recanto dos Fernandes',
            'cidade' => 'Pouso Alegre',
            'uf' => 'MG',
        ]);

        $response = $this->postJson('/api/auth/login', [
            'email' => 'marcos.souza@exemplo.com',
            'password' => 'SenhaForte@2026!',
            'remember' => true,
        ]);

        $response->assertStatus(200)
            ->assertJsonPath('user.name', 'Marcos de Souza')
            ->assertJsonPath('user.email', 'marcos.souza@exemplo.com')
            ->assertJsonPath('user.address.bairro', 'Recanto dos Fernandes');

        $this->assertAuthenticatedAs($user);

        // Verify remember token was set
        $user->refresh();
        $this->assertNotNull($user->getRememberToken());
    }

    public function test_login_fails_with_invalid_credentials(): void
    {
        User::create([
            'name' => 'Marcos de Souza',
            'email' => 'marcos.souza@exemplo.com',
            'password' => Hash::make('SenhaForte@2026!'),
        ]);

        $response = $this->postJson('/api/auth/login', [
            'email' => 'marcos.souza@exemplo.com',
            'password' => 'SenhaErrada#999',
        ]);

        $response->assertStatus(401)
            ->assertJsonPath('message', 'Credenciais inválidas. Verifique seu e-mail e senha.');

        $this->assertGuest();
    }

    public function test_me_returns_authenticated_user_profile(): void
    {
        $user = User::create([
            'name' => 'Marcos de Souza',
            'email' => 'marcos.souza@exemplo.com',
            'password' => Hash::make('SenhaForte@2026!'),
        ]);

        $user->address()->create([
            'cep' => '37550000',
            'logradouro' => 'Rua Principal',
            'numero' => '42',
            'bairro' => 'Recanto dos Fernandes',
            'cidade' => 'Pouso Alegre',
            'uf' => 'MG',
        ]);

        $response = $this->actingAs($user)->getJson('/api/auth/me');

        $response->assertStatus(200)
            ->assertJsonPath('user.name', 'Marcos de Souza')
            ->assertJsonPath('user.address.bairro', 'Recanto dos Fernandes');
    }

    public function test_me_returns_401_when_unauthenticated(): void
    {
        $response = $this->getJson('/api/auth/me');

        $response->assertStatus(401);
    }
}
