# Documentação da API - Sistema de Contas Bancárias

## Rotas de Usuário

- **POST /users**: Cria um novo usuário.
- **GET /users**: Retorna todos os usuários.
- **GET /users/{id}**: Retorna um usuário específico.
- **PUT /users/{id}**: Atualiza um usuário existente.
- **DELETE /users/{id}**: Deleta um usuário específico.

## Rotas de Autenticação

- **POST /login**: Realiza login do usuário e retorna um token.

## Rotas de Conta Bancária

- **POST /accounts**
    - **Descrição**: Cria uma nova conta bancária.
    - **Parâmetros**: 
        - `nome` (string) - Nome da conta bancária.
        - `saldo` (float) - Saldo inicial da conta.
    - **Resposta**: `{"success": "Conta bancária cadastrada com sucesso."}`

- **GET /accounts**
    - **Descrição**: Retorna todas as contas bancárias.
    - **Resposta**: Lista de contas bancárias.

- **GET /accounts/{id}**
    - **Descrição**: Retorna uma conta bancária específica.
    - **Resposta**: Dados da conta bancária ou erro 404 se encontrar.

- **PUT /accounts/{id}**
    - **Descrição**: Atualiza uma conta bancaria existente.
    - **Parâmetros**: 
        - `nome` (string) - Nome da conta bancária (opcional).
        - `saldo` (float) - Saldo da conta (opcional).
    - **Resposta**: `{"success": "Conta bancária atualizada com sucesso."}`

- **DELETE /accounts/{id}**
    - **Descrição**: Deleta uma conta bancária específica.
    - **Resposta**: `{"success": "Conta bancária deletada com sucesso."}` ou erro 404 se nao encontrar