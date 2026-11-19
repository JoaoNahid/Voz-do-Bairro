# Bug Verification: Falha de Login por Tabela 'cache' Inexistente no PostgreSQL

- **Slug**: login-cache-table-missing
- **Tested**: 2026-11-19T18:28:30-03:00
- **Assessment**: ./assessment.md
- **Fix**: ./fix.md
- **Result**: verified

## Summary

O bug original não reproduz mais. A requisição de autenticação para `/api/auth/login` utiliza o RateLimiter do Laravel interagindo com sucesso com as tabelas de cache no PostgreSQL sem disparar nenhum erro de SQL ou exceção 500. Todos os testes unitários e de integração passaram, assim como a compilação de assets do frontend.

## Checks Performed

| Check | Command / Action | Result | Notes |
|-------|------------------|--------|-------|
| Reproduction (post-fix) | `curl -s -X POST http://localhost:8000/api/auth/login -H "Content-Type: application/json" -H "Accept: application/json" -d '{"email":"joaonahiddev@gmail.com","password":"wrongpassword"}'` | pass | Retornou HTTP 401 com mensagem controlada de credenciais inválidas. Registro de rate limiting criado na tabela `cache` do PostgreSQL. |
| New / updated tests | `PHP_INI_SCAN_DIR=/etc/php/conf.d:/home/nahid/.config/php php -d extension=pdo_sqlite -d extension=sqlite3 vendor/bin/phpunit --filter CacheTableTest` | pass | 2 testes, 5 asserções confirmando existência do schema e leitura/escrita no cache. |
| Regression suite | `PHP_INI_SCAN_DIR=/etc/php/conf.d:/home/nahid/.config/php php -d extension=pdo_sqlite -d extension=sqlite3 vendor/bin/phpunit` | pass | 15 testes, 70 asserções passando sem regressões. |
| Frontend build | `npm run build` | pass | Compilação de produção com Vite v6 concluída com sucesso (2.19s). |

## Output Excerpts

### Reproduction (post-fix)
```json
HTTP/1.1 401 Unauthorized
{"message":"Credenciais inválidas. Verifique seu e-mail e senha."}
```
Verificação no PostgreSQL via `psql`:
```text
key                                                             | expiration
----------------------------------------------------------------+------------
voz_do_bairro_cache_joaonahiddev@gmail.com|127.0.0.1:timer      | 1763587584
```

### New Tests (`CacheTableTest`)
```text
PHPUnit 11.5.54 by Sebastian Bergmann and contributors.

..                                                                  2 / 2 (100%)

Time: 00:00.177, Memory: 26.00 MB

OK (2 tests, 5 assertions)
```

### Full Regression Suite
```text
PHPUnit 11.5.54 by Sebastian Bergmann and contributors.

...............                                                   15 / 15 (100%)

Time: 00:00.320, Memory: 32.00 MB

OK (15 tests, 70 assertions)
```

### Frontend Build
```text
vite v6.4.5 building for production...
✓ 1683 modules transformed.
public/build/manifest.json              0.27 kB │ gzip:  0.15 kB
public/build/assets/app-C6RmUwdJ.css   43.07 kB │ gzip:  7.79 kB
public/build/assets/app-D_KaDm56.js   248.48 kB │ gzip: 81.73 kB
✓ built in 2.19s
```

## Residual Risks

- Nenhuma limitação conhecida para o driver de cache em banco de dados em instâncias PostgreSQL standard.
- Em ambientes com alta concorrência de acessos, pode-se avaliar a transição futura do driver de cache de `database` para `redis`, mas para a infraestrutura atual com PostgreSQL o driver `database` atende perfeitamente com a tabela provisionada.

## Recommendation

Close the bug — verified end-to-end. O fluxo de login e rate limiting está totalmente funcional e validado tanto no PostgreSQL real em container quanto na suíte de testes automatizados.
