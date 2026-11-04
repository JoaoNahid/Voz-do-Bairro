<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Services\CepService;
use Illuminate\Http\JsonResponse;

class CepController extends Controller
{
    public function __construct(
        private readonly CepService $cepService
    ) {}

    /**
     * Consult address by CEP.
     */
    public function show(string $cep): JsonResponse
    {
        $result = $this->cepService->lookup($cep);

        if (! $result['success']) {
            $status = str_contains($result['message'] ?? '', 'não localizado') ? 404 : 422;

            return response()->json([
                'message' => $result['message'],
            ], $status);
        }

        return response()->json($result['data']);
    }
}
