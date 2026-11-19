# Bug Fix: Falha de Login por Tabela 'cache' Inexistente no PostgreSQL

- **Slug**: login-cache-table-missing
- **Fixed**: 2026-11-19T18:26:30-03:00
- **Assessment**: ./assessment.md
- **Status**: applied

## Summary

Criada a migration para as tabelas `cache` e `cache_locks` no PostgreSQL, configurado explicitamente `CACHE_STORE=database` no ambiente e validado o funcionamento do `RateLimiter` durante o login, eliminando o erro `SQLSTATE[42P01]: Undefined table: 7 ERROR: relation "cache" does not exist`.

## Changes

| File | Change | Notes |
|------|--------|-------|
| `database/migrations/2026_11_19_182308_create_cache_table.php` | added | Criação das tabelas `cache` e `cache_locks` |
| `.env` | modified | Inclusão de `CACHE_STORE=database` |
| `.env.example` | modified | Inclusão de `CACHE_STORE=database` |
| `tests/Feature/CacheTableTest.php` | added | Teste automatizado de integridade das tabelas de cache e `RateLimiter` |

## Diff Highlights

```php
Schema::create('cache', function (Blueprint $table) {
    $table->string('key')->primary();
    $table->mediumText('value');
    $table->integer('expiration');
});

Schema::create('cache_locks', function (Blueprint $table) {
    $table->string('key')->primary();
    $table->string('owner');
    $table->integer('expiration');
});
```

## Tests Added or Updated

- `tests/Feature/CacheTableTest.php::test_cache_and_cache_locks_tables_exist` — Valida a existência das tabelas `cache` e `cache_locks`.
- `tests/Feature/CacheTableTest.php::test_rate_limiter_can_store_and_clear_throttle_keys` — Valida que o `RateLimiter` incrementa e limpa chaves de throttle sem erros.

## Local Verification

- Migrations no PostgreSQL: `php artisan migrate` → `2026_11_19_182308_create_cache_table DONE`.
- Verificação direta no banco PostgreSQL: `docker exec voz-do-bairro-postgres psql -c "select * from cache;"` → chaves de rate limit gravadas com sucesso.
- Requisição HTTP real ao endpoint de login: `POST /api/auth/login` → HTTP 401 Credenciais inválidas retornado corretamente com resposta JSON sem qualquer erro 500 ou falha de SQL.
- Suíte completa de testes: `vendor/bin/phpunit` → 15 testes, 70 assertions (100% OK).

## Deviations from Assessment

Nenhuma. A correção seguiu exatamente o plano preferencial proposto no `assessment.md`.

## Follow-ups

- Manter o container PostgreSQL em execução (`docker compose up -d`) para suporte contínuo ao cache e sessões.
