# Configuração Docker para API Laravel

Este projeto está configurado para rodar com Docker usando PostgreSQL como banco de dados.

## Pré-requisitos

-   Docker
-   Docker Compose

## Como executar

1. **Construir e iniciar os containers:**

    ```bash
    docker-compose up --build
    ```

2. **Acessar a aplicação:**
    - API Laravel: http://localhost:8000
    - Banco PostgreSQL: localhost:5433

## Configurações

### Banco de Dados PostgreSQL

-   **Host:** postgres (dentro do container) / localhost (externo)
-   **Porta:** 5432 (dentro do container) / 5433 (externo)
-   **Usuário:** postgres
-   **Senha:** 12345
-   **Database:** testdb

### Variáveis de Ambiente

As seguintes variáveis estão configuradas no docker-compose.yml:

-   `DB_CONNECTION=pgsql`
-   `DB_HOST=postgres`
-   `DB_PORT=5432`
-   `DB_DATABASE=testdb`
-   `DB_USERNAME=postgres`
-   `DB_PASSWORD=12345`

## Comandos úteis

### Parar os containers:

```bash
docker-compose down
```

### Ver logs:

```bash
docker-compose logs -f laravel
```

### Executar comandos no container:

```bash
docker-compose exec laravel php artisan migrate
docker-compose exec laravel php artisan tinker
```

### Acessar o banco de dados:

```bash
docker-compose exec postgres psql -U postgres -d testdb
```

## Estrutura dos Containers

-   **laravel_app:** Container da aplicação Laravel (porta 8000)
-   **postgres_db_php:** Container do PostgreSQL (porta 5433)

## Script de Inicialização

O container Laravel executa automaticamente:

1. Aguarda o banco de dados estar pronto
2. Copia o arquivo .env.example se necessário
3. Gera a APP_KEY se necessário
4. Limpa o cache
5. Executa as migrações
6. Inicia o servidor Laravel
