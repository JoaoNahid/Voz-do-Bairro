<?php

declare(strict_types=1);

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class CepService
{
    private const BASE_URL = 'https://opencep.com/v1';
    private const TIMEOUT_SECONDS = 3;

    /**
     * Look up address details by Brazilian postal code (CEP).
     *
     * @param string $cep
     * @return array{success: bool, data?: array<string, mixed>, message?: string, is_pilot_region?: bool}
     */
    public function lookup(string $cep): array
    {
        $sanitizedCep = preg_replace('/\D/', '', $cep);

        if (strlen($sanitizedCep) !== 8) {
            return [
                'success' => false,
                'message' => 'O CEP informado deve conter exatamente 8 dígitos.',
            ];
        }

        try {
            $response = Http::timeout(self::TIMEOUT_SECONDS)
                ->acceptJson()
                ->get(self::BASE_URL . "/{$sanitizedCep}");

            if ($response->notFound()) {
                return [
                    'success' => false,
                    'message' => 'CEP não localizado na base postal.',
                ];
            }

            if (! $response->successful()) {
                return [
                    'success' => false,
                    'message' => 'Serviço de consulta de CEP temporariamente indisponível.',
                ];
            }

            $payload = $response->json();

            if (! is_array($payload) || empty($payload['cep'])) {
                return [
                    'success' => false,
                    'message' => 'Resposta inválida do serviço de consulta de CEP.',
                ];
            }

            $cidade = (string) ($payload['localidade'] ?? '');
            $uf = strtoupper((string) ($payload['uf'] ?? ''));

            $isPilot = $this->isPilotRegion($cidade, $uf);

            return [
                'success' => true,
                'data' => [
                    'cep' => (string) ($payload['cep'] ?? $sanitizedCep),
                    'logradouro' => (string) ($payload['logradouro'] ?? ''),
                    'complemento' => (string) ($payload['complemento'] ?? ''),
                    'bairro' => (string) ($payload['bairro'] ?? ''),
                    'cidade' => $cidade,
                    'uf' => $uf,
                    'is_pilot_region' => $isPilot,
                ],
            ];
        } catch (\Throwable $e) {
            Log::warning("Falha na consulta ao OpenCEP: {$e->getMessage()}", ['cep' => $sanitizedCep]);

            return [
                'success' => false,
                'message' => 'Não foi possível consultar o CEP no momento. Você pode preencher o endereço manualmente.',
            ];
        }
    }

    /**
     * Check if city and state belong to the initial pilot area (Pouso Alegre / MG).
     */
    public function isPilotRegion(string $cidade, string $uf): bool
    {
        $cityNorm = mb_strtolower(trim($cidade));
        $ufNorm = mb_strtoupper(trim($uf));

        return ($cityNorm === 'pouso alegre' || str_contains($cityNorm, 'pouso alegre')) && $ufNorm === 'MG';
    }
}
