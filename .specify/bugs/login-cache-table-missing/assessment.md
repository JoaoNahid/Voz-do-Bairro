# Bug Assessment: Falha de Login por Tabela 'cache' Inexistente no PostgreSQL

- **Slug**: login-cache-table-missing
- **Created**: 2026-11-19T18:22:00-03:00
- **Source**: Pasted text
- **Verdict**: valid
- **Severity**: high

## Report (verbatim or summarized)

> "tive um erro ao efetuar login: 'SQLSTATE[42P01]: Undefined table: 7 ERROR: relation "cache" does not exist LINE 1: select * from "cache" where "key" in ($1) ^ (Connection: pgsql, SQL: select * from "cache" where "key" in (voz_do_bairro_cache_joaonahiddev@gmail.com|127.0.0.1))'. Verifique o que está acontecendo e ja teste todo o fluxo de autenticação para ver se encontra algum outro bug"

## Symptom

Ao tentar realizar o login na aplicação através do endpoint `POST /api/auth/login`, o sistema falha com erro HTTP 500 devido a uma exceção de banco de dados no PostgreSQL: `SQLSTATE[42P01]: Undefined table: 7 ERROR: relation "cache" does not exist`. O comportamento esperado é que a tentativa de autenticação ocorra sem erros de infraestrutura, autenticando o usuário se as credenciais forem válidas ou retornando 401 caso inválidas.

## Reproduction

1. Ter o banco de dados configurado no PostgreSQL (`DB_CONNECTION=pgsql`) sem tabela de cache criada.
2. Iniciar a aplicação web (`php artisan serve`).
3. Enviar uma requisição de login para `POST /api/auth/login` (seja pela interface do `AuthDialog.vue` ou via `curl/fetch`) com e-mail e senha.
4. O Laravel tenta consultar o limitador de requisições (`RateLimiter::tooManyAttempts`) e falha imediatamente com `relation "cache" does not exist`.

## Suspected Code Paths

- `app/Http/Controllers/AuthController.php:57-79` — O método `login` invoca `RateLimiter::tooManyAttempts($throttleKey, 5)`, `RateLimiter::hit($throttleKey, 60)` e `RateLimiter::clear($throttleKey)`.
- `config/cache.php` / `.env` — No Laravel 11, o driver padrão de cache é `database` quando a variável de ambiente `CACHE_STORE` não é informada explicitamente.
- `database/migrations/` — Não existe migration para a tabela `cache` e `cache_locks` no repositório.

## Root Cause Hypothesis

No Laravel 11, o gerenciador de limitação de taxa (`Illuminate\Support\Facades\RateLimiter`) delega o armazenamento dos contadores de tentativa ao repositório padrão de cache da aplicação. Quando a conexão foi alterada de SQLite em memória/arquivo para PostgreSQL (`DB_CONNECTION=pgsql`), o Laravel assumiu a configuração padrão de cache `CACHE_STORE=database`. Como o esquema do PostgreSQL contém apenas as tabelas `users`, `addresses` e `sessions`, a tabela `cache` não existe no banco, disparando o erro SQL no momento em que a query de rate limit é executada. Confiança: **Alta**.

## Proposed Remediation

**Preferred**:
1. Gerar e aplicar a migration oficial do Laravel para o banco de dados de cache (`php artisan make:cache-table` ou migration `create_cache_table.php` contendo as tabelas `cache` e `cache_locks`).
2. Configurar explicitamente no arquivo `.env` e `.env.example` a variável `CACHE_STORE=database` (ou disponibilizar a opção `CACHE_STORE=file` para ambientes locais sem persistência de cache).
3. Executar as migrações pendentes no PostgreSQL (`php artisan migrate`).

**Alternatives**:
- *Alterar `CACHE_STORE=file` no `.env`*: Evita criar tabelas no banco de dados, mas não escala caso a aplicação seja hospedada em múltiplos nós/containers compartilhados sem disco sincronizado.

**Files likely to change**:
- `database/migrations/2026_11_19_000001_create_cache_table.php` (nova migration)
- `.env` e `.env.example` (definição explícita de `CACHE_STORE=database`)

**Tests to add or update**:
- Executar teste de Feature `tests/Feature/LoginTest.php` garantindo que o `RateLimiter` funcione corretamente sem exceções de SQL tanto em SQLite (ambiente de testes) quanto em PostgreSQL.

## Risks & Considerations

- **Risco Baixo**: A criação da tabela `cache` é uma operação estritamente aditiva (Princípio II da Constituição) e não interfere nos dados existentes de `users`, `addresses` ou `sessions`.
- **Análise do Restante do Fluxo de Autenticação**:
  - `POST /api/auth/register`: Não utiliza `RateLimiter`, apenas transação de `users` e `addresses`. Funcionando normalmente.
  - `POST /api/auth/logout`: Apenas invalida sessão web. Funcionando normalmente.
  - `PUT /api/profile`: Validação de senha atual e atualização cadastral. Funcionando normalmente.
  - `PUT /api/profile/password`: Atualização de senha. Funcionando normalmente.
  - `DELETE /api/profile/account`: Exclusão atômica de conta e endereço. Funcionando normalmente.
  - O único ponto de falha no fluxo completo é a ausência da tabela `cache` utilizada pelo `RateLimiter` no `AuthController::login`.

## Open Questions

- Nenhuma dúvida aberta. Causa raiz plenamente identificada e isolada.
