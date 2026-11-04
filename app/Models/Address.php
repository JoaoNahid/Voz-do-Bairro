<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Address extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'addressable_type',
        'addressable_id',
        'cep',
        'logradouro',
        'numero',
        'complemento',
        'bairro',
        'cidade',
        'uf',
    ];

    /**
     * Get the owning addressable model (User, Report, etc.).
     */
    public function addressable(): MorphTo
    {
        return $this->morphTo();
    }

    /**
     * Determine if the address is within the initial pilot region (Pouso Alegre, MG).
     */
    public function isPilotRegion(): bool
    {
        $cityNorm = mb_strtolower(trim($this->cidade));
        $ufNorm = mb_strtoupper(trim($this->uf));

        return ($cityNorm === 'pouso alegre' || str_contains($cityNorm, 'pouso alegre')) && $ufNorm === 'MG';
    }
}
