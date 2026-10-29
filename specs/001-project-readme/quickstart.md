# Quickstart & Validation Guide: Project Presentation README

**Feature**: `001-project-readme`
**Date**: 2026-10-28

Este guia orienta a validação end-to-end da apresentação do projeto no [README.md](../../README.md), garantindo conformidade com a especificação ([spec.md](spec.md)) e os contratos de documentação ([contracts/readme-schema.json](contracts/readme-schema.json)).

---

## Pré-requisitos

- Git instalado
- Leitor / Renderizador Markdown (VS Code Preview, GitHub Web ou CLI markdown linter)

---

## Cenários de Validação

### 1. Validação de Integridade do Arquivo README.md
Verificar se o arquivo existe na raiz do repositório e possui tamanho/conteúdo consistente:

```bash
test -f README.md && echo "README.md exists!"
wc -l README.md
```

**Resultado Esperado**:
- O arquivo existe e possui mais de 100 linhas estruturadas.

---

### 2. Validação dos Tópicos Acadêmicos Extensionistas
Verificar a presença dos tópicos definidos no documento de proposta da universidade:

```bash
# Verificar presença dos autores e RUs
grep -E "(Ariane Barbosa Sóllner Vieira|João Eduardo de Godoi Nahid)" README.md
grep -E "(4880805|4896259)" README.md

# Verificar menção ao bairro de aplicação
grep -i "Recanto dos Fernandes" README.md

# Verificar presença de todas as 7 ODSs selecionadas
grep -E "(ODS.*03|ODS.*06|ODS.*09|ODS.*10|ODS.*11|ODS.*16|ODS.*17)" README.md
```

**Resultado Esperado**:
- Todos os termos acadêmicos, nomes dos autores, RUs e as 7 ODSs são encontrados com sucesso.

---

### 3. Validação dos Requisitos e Regra de Quórum (60%)
Verificar se as regras de negócio comunitárias estão devidamente documentadas:

```bash
# Verificar catálogo de requisitos RF e RNF
grep -E "RF0[1-5]" README.md
grep -E "RNF0[1-5]" README.md

# Verificar a regra de 60% para exibição pública na página principal
grep -E "60%" README.md
```

**Resultado Esperado**:
- Todos os requisitos RF01 a RF05 e RNF01 a RNF05 constam no documento, com destaque para a regra de relevância pública com 60% de quórum.

---

### 4. Validação das Convenções de Git e Remoto Oficial
Confirmar as instruções de GitFlow, padrão de commits e endereço remoto:

```bash
grep -i "GitFlow" README.md
grep -i "https://github.com/JoaoNahid/Voz-do-Bairro.git" README.md
grep -E "(feat|fix|docs|test|refactor)" README.md
```

**Resultado Esperado**:
- Repositório remoto oficial e exemplos de mensagens de commit híbridas devidamente listados.
