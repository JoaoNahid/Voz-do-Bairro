<?php

declare(strict_types=1);

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Validator;

class UpdateProfileRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user() !== null;
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
        $userId = $this->user()?->id;

        return [
            'name' => ['required', 'string', 'min:3', 'max:255'],
            'email' => ['required', 'string', 'email:rfc', 'max:255', "unique:users,email,{$userId}"],
            'current_password' => ['nullable', 'string'],
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
     * Additional validation to enforce current password if email changes.
     */
    public function after(): array
    {
        return [
            function (Validator $validator) {
                $user = $this->user();
                if (! $user) {
                    return;
                }

                $newEmail = (string) $this->input('email');
                if (strtolower($newEmail) !== strtolower($user->email)) {
                    $currentPassword = (string) $this->input('current_password');

                    if (empty($currentPassword)) {
                        $validator->errors()->add(
                            'current_password',
                            'A senha atual é obrigatória para autorizar a alteração do endereço de e-mail.'
                        );
                    } elseif (! Hash::check($currentPassword, $user->password)) {
                        $validator->errors()->add(
                            'current_password',
                            'A senha atual informada está incorreta.'
                        );
                    }
                }
            },
        ];
    }

    /**
     * Custom validation messages.
     */
    public function messages(): array
    {
        return [
            'name.required' => 'O nome completo é obrigatório.',
            'name.min' => 'O nome deve ter pelo menos 3 caracteres.',
            'email.required' => 'O e-mail é obrigatório.',
            'email.email' => 'Informe um endereço de e-mail válido.',
            'email.unique' => 'Este e-mail já está sendo utilizado por outro morador.',
            'cep.required' => 'O CEP é obrigatório.',
            'cep.size' => 'O CEP deve conter 8 dígitos numéricos.',
            'logradouro.required' => 'O logradouro é obrigatório.',
            'numero.required' => 'O número do imóvel é obrigatório.',
            'bairro.required' => 'O bairro é obrigatório.',
            'cidade.required' => 'A cidade é obrigatória.',
            'uf.required' => 'A UF é obrigatória.',
            'uf.size' => 'A UF deve ter exatamente 2 caracteres.',
        ];
    }
}
