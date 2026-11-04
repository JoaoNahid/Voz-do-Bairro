<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Models\Address;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class ProfileTest extends TestCase
{
    use RefreshDatabase;

    public function test_citizen_can_update_profile_and_address(): void
    {
        $user = User::create([
            'name' => 'Renata Lima',
            'email' => 'renata.lima@exemplo.com',
            'password' => Hash::make('SenhaAntiga@2026!'),
        ]);

        $user->address()->create([
            'cep' => '37550000',
            'logradouro' => 'Rua Velha',
            'numero' => '10',
            'bairro' => 'Centro',
            'cidade' => 'Pouso Alegre',
            'uf' => 'MG',
        ]);

        $response = $this->actingAs($user)->putJson('/api/profile', [
            'name' => 'Renata Lima Silva',
            'email' => 'renata.lima@exemplo.com', // unchanged email, no current_password needed
            'cep' => '37550100',
            'logradouro' => 'Avenida Nova',
            'numero' => '99',
            'complemento' => 'Apto 101',
            'bairro' => 'Recanto dos Fernandes',
            'cidade' => 'Pouso Alegre',
            'uf' => 'MG',
        ]);

        $response->assertStatus(200)
            ->assertJsonPath('user.name', 'Renata Lima Silva')
            ->assertJsonPath('user.address.bairro', 'Recanto dos Fernandes')
            ->assertJsonPath('user.address.numero', '99');

        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'name' => 'Renata Lima Silva',
        ]);

        $this->assertDatabaseHas('addresses', [
            'addressable_type' => User::class,
            'addressable_id' => $user->id,
            'logradouro' => 'Avenida Nova',
            'bairro' => 'Recanto dos Fernandes',
        ]);
    }

    public function test_changing_email_requires_current_password(): void
    {
        $user = User::create([
            'name' => 'Renata Lima',
            'email' => 'renata.lima@exemplo.com',
            'password' => Hash::make('SenhaAntiga@2026!'),
        ]);

        $user->address()->create([
            'cep' => '37550000',
            'logradouro' => 'Rua Velha',
            'numero' => '10',
            'bairro' => 'Centro',
            'cidade' => 'Pouso Alegre',
            'uf' => 'MG',
        ]);

        // Attempting to change email without current_password
        $response = $this->actingAs($user)->putJson('/api/profile', [
            'name' => 'Renata Lima',
            'email' => 'novo.email@exemplo.com',
            'cep' => '37550000',
            'logradouro' => 'Rua Velha',
            'numero' => '10',
            'bairro' => 'Centro',
            'cidade' => 'Pouso Alegre',
            'uf' => 'MG',
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['current_password']);

        // Attempting with wrong current_password
        $wrongResponse = $this->actingAs($user)->putJson('/api/profile', [
            'name' => 'Renata Lima',
            'email' => 'novo.email@exemplo.com',
            'current_password' => 'SenhaIncorreta#123',
            'cep' => '37550000',
            'logradouro' => 'Rua Velha',
            'numero' => '10',
            'bairro' => 'Centro',
            'cidade' => 'Pouso Alegre',
            'uf' => 'MG',
        ]);

        $wrongResponse->assertStatus(422)
            ->assertJsonValidationErrors(['current_password']);

        // Success with correct current_password
        $okResponse = $this->actingAs($user)->putJson('/api/profile', [
            'name' => 'Renata Lima',
            'email' => 'novo.email@exemplo.com',
            'current_password' => 'SenhaAntiga@2026!',
            'cep' => '37550000',
            'logradouro' => 'Rua Velha',
            'numero' => '10',
            'bairro' => 'Centro',
            'cidade' => 'Pouso Alegre',
            'uf' => 'MG',
        ]);

        $okResponse->assertStatus(200)
            ->assertJsonPath('user.email', 'novo.email@exemplo.com');
    }

    public function test_citizen_can_update_password(): void
    {
        $user = User::create([
            'name' => 'Renata Lima',
            'email' => 'renata.lima@exemplo.com',
            'password' => Hash::make('SenhaAntiga@2026!'),
        ]);

        $response = $this->actingAs($user)->putJson('/api/profile/password', [
            'current_password' => 'SenhaAntiga@2026!',
            'password' => 'NovaSenha@2026#',
            'password_confirmation' => 'NovaSenha@2026#',
        ]);

        $response->assertStatus(200);

        $user->refresh();
        $this->assertTrue(Hash::check('NovaSenha@2026#', $user->password));
    }

    public function test_citizen_can_delete_account_and_polymorphic_address(): void
    {
        $user = User::create([
            'name' => 'Renata Lima',
            'email' => 'renata.lima@exemplo.com',
            'password' => Hash::make('SenhaAntiga@2026!'),
        ]);

        $user->address()->create([
            'cep' => '37550000',
            'logradouro' => 'Rua Velha',
            'numero' => '10',
            'bairro' => 'Centro',
            'cidade' => 'Pouso Alegre',
            'uf' => 'MG',
        ]);

        $userId = $user->id;

        // Fails with wrong password
        $wrongResponse = $this->actingAs($user)->deleteJson('/api/profile/account', [
            'current_password' => 'SenhaErrada#000',
        ]);
        $wrongResponse->assertStatus(422);

        // Succeeds with correct password
        $response = $this->actingAs($user)->deleteJson('/api/profile/account', [
            'current_password' => 'SenhaAntiga@2026!',
        ]);

        $response->assertStatus(200)
            ->assertJsonPath('message', 'Sua conta e seus dados foram excluídos definitivamente.');

        $this->assertDatabaseMissing('users', ['id' => $userId]);
        $this->assertDatabaseMissing('addresses', [
            'addressable_type' => User::class,
            'addressable_id' => $userId,
        ]);
        $this->assertGuest();
    }
}
