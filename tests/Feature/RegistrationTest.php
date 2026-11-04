<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Models\Address;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RegistrationTest extends TestCase
{
    use RefreshDatabase;

    public function test_citizen_can_register_with_polymorphic_address(): void
    {
        $payload = [
            'name' => 'Maria de Lourdes Ferreira',
            'email' => 'maria.ferreira@exemplo.com',
            'password' => 'SenhaForte@2026!',
            'password_confirmation' => 'SenhaForte@2026!',
            'cep' => '37550000',
            'logradouro' => 'Rua Comendador José Garcia',
            'numero' => '120',
            'complemento' => 'Apto 302',
            'bairro' => 'Centro',
            'cidade' => 'Pouso Alegre',
            'uf' => 'MG',
        ];

        $response = $this->postJson('/api/auth/register', $payload);

        $response->assertStatus(201)
            ->assertJsonPath('user.name', 'Maria de Lourdes Ferreira')
            ->assertJsonPath('user.email', 'maria.ferreira@exemplo.com')
            ->assertJsonPath('user.address.bairro', 'Centro')
            ->assertJsonPath('user.address.cidade', 'Pouso Alegre')
            ->assertJsonPath('user.address.is_pilot_region', true);

        $this->assertDatabaseHas('users', [
            'email' => 'maria.ferreira@exemplo.com',
            'name' => 'Maria de Lourdes Ferreira',
        ]);

        $user = User::where('email', 'maria.ferreira@exemplo.com')->first();
        $this->assertNotNull($user);

        // Verify polymorphic relationship
        $this->assertDatabaseHas('addresses', [
            'addressable_type' => User::class,
            'addressable_id' => $user->id,
            'cep' => '37550000',
            'bairro' => 'Centro',
            'cidade' => 'Pouso Alegre',
        ]);

        $this->assertNotNull($user->address);
        $this->assertInstanceOf(Address::class, $user->address);
        $this->assertTrue($user->address->isPilotRegion());

        // Check authentication
        $this->assertAuthenticatedAs($user);
    }

    public function test_registration_fails_when_email_already_registered(): void
    {
        User::create([
            'name' => 'Usuário Existente',
            'email' => 'duplicado@exemplo.com',
            'password' => bcrypt('SenhaForte@2026!'),
        ]);

        $payload = [
            'name' => 'Outro Usuário',
            'email' => 'duplicado@exemplo.com',
            'password' => 'SenhaForte@2026!',
            'password_confirmation' => 'SenhaForte@2026!',
            'cep' => '37550000',
            'logradouro' => 'Rua Teste',
            'numero' => '10',
            'bairro' => 'Centro',
            'cidade' => 'Pouso Alegre',
            'uf' => 'MG',
        ];

        $response = $this->postJson('/api/auth/register', $payload);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['email']);
    }

    public function test_registration_fails_when_password_does_not_meet_complexity(): void
    {
        $payload = [
            'name' => 'Munícipe Teste',
            'email' => 'fraco@exemplo.com',
            'password' => 'simples123', // missing uppercase, special char
            'password_confirmation' => 'simples123',
            'cep' => '37550000',
            'logradouro' => 'Rua Teste',
            'numero' => '10',
            'bairro' => 'Centro',
            'cidade' => 'Pouso Alegre',
            'uf' => 'MG',
        ];

        $response = $this->postJson('/api/auth/register', $payload);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['password']);
    }

    public function test_registration_allows_national_cep_with_pilot_flag_false(): void
    {
        $payload = [
            'name' => 'Cidadão de Outra Cidade',
            'email' => 'externo@exemplo.com',
            'password' => 'SenhaForte@2026!',
            'password_confirmation' => 'SenhaForte@2026!',
            'cep' => '01001000',
            'logradouro' => 'Praça da Sé',
            'numero' => '1',
            'bairro' => 'Sé',
            'cidade' => 'São Paulo',
            'uf' => 'SP',
        ];

        $response = $this->postJson('/api/auth/register', $payload);

        $response->assertStatus(201)
            ->assertJsonPath('user.address.is_pilot_region', false);
    }
}
