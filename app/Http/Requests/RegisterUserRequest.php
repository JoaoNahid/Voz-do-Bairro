<?php

declare(strict_types=1);

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;

class RegisterUserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation(): void
    {
        if ($this->has('cep')) {
            $this->merge([
                'cep' => preg_replace('/\D/', '', (string) $this->input('cep')),
            ]);
        }
        if ($this->has('uf')) {
            $this->merge([
                'uf' => strtoupper(trim((string) $this->input('uf'))),
            ]);
        }
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'min:3', 'max:255'],
            'email' => ['required', 'string', 'email:rfc', 'max:255', 'unique:users,email'],
            'password' => [
                'required',
                'confirmed',
                Password::min(8)
                    ->letters()
                    ->mixedCase()
                    ->numbers()
                    ->symbols(),
            ],
            'cep' => ['required', 'string', 'size:8'],
            'logradouro' => ['required', 'string', 'max:255'],
            'numero' => ['required', 'string', 'max:20'],
            'complemento' => ['nullable', 'string', 'max:100'],
            'bairro' => ['required', 'string', 'max:100'],
            'cidade' => ['required', 'string', 'max:100'],
            'uf' => ['required', 'string', 'size:2'],
        ];
    }

    /**
     * Custom validation messages in Portuguese.
     */
    public function messages(): array
    {
        return [
            'name.required' => 'O nome completo é obrigatório.',
            'name.min' => 'O nome deve ter pelo menos 3 caracteres.',
            'email.required' => 'O e-mail é obrigatório.',
            'email.email' => 'Informe um endereço de e-mail válido.',
            'email.unique' => 'Este e-mail já está cadastrado na plataforma.',
            'password.required' => 'A senha é obrigatória.',
            'password.confirmed' => 'A confirmação da senha não confere.',
            'cep.required' => 'O CEP é obrigatório.',
            'cep.size' => 'O CEP deve conter 8 dígitos numéricos.',
            'logradouro.required' => 'O logradouro (rua/avenida) é obrigatório.',
            'numero.required' => 'O número do imóvel é obrigatório (ou informe S/N).',
            'bairro.required' => 'O bairro é obrigatório.',
            'cidade.required' => 'A cidade é obrigatória.',
            'uf.required' => 'O estado (UF) é obrigatório.',
            'uf.size' => 'A UF deve ter exatamente 2 caracteres.',
        ];
    }
}
