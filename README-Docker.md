# Docker Setup para Laravel 12 + PostgreSQL

Este projeto inclui uma configuração Docker completa para rodar uma aplicação Laravel 12 com PHP 8.2 e PostgreSQL 16.

## Estrutura dos Containers

-   **app**: Container PHP 8.2 com Laravel 12
-   **postgres**: Container PostgreSQL 16

## Como usar

### 1. Construir e iniciar os containers

```bash
docker-compose up --build
```

### 2. Acessar a aplicação

A aplicação estará disponível em: http://localhost:5002

### 3. Acessar o banco de dados

-   **Host**: localhost
-   **Porta**: 5432
-   **Usuário**: postgres
-   **Senha**: 12345
-   **Database**: testdb

### 4. Comandos úteis

#### Executar comandos no container da aplicação

```bash
# Acessar o container
docker-compose exec app bash

# Executar migrations
docker-compose exec app php artisan migrate

# Executar seeders
docker-compose exec app php artisan db:seed

# Limpar cache
docker-compose exec app php artisan cache:clear
```

#### Parar os containers

```bash
docker-compose down
```

#### Parar e remover volumes (cuidado: isso apaga o banco)

```bash
docker-compose down -v
```

## Configurações

### Variáveis de Ambiente

As variáveis de ambiente estão configuradas no `docker-compose.yml`:

-   `DB_CONNECTION=pgsql`
-   `DB_HOST=postgres`
-   `DB_PORT=5432`
-   `DB_DATABASE=testdb`
-   `DB_USERNAME=postgres`
-   `DB_PASSWORD=12345`

### Portas

-   **Aplicação Laravel**: 5002
-   **PostgreSQL**: 5432

### Volumes

-   Dados do PostgreSQL: `pgdata`
-   Código da aplicação: montado do diretório atual

## Troubleshooting

### Se a aplicação não conectar ao banco

1. Verifique se o container do PostgreSQL está saudável:

    ```bash
    docker-compose ps
    ```

2. Aguarde o healthcheck do PostgreSQL completar antes de acessar a aplicação

### Se precisar reinstalar dependências

```bash
docker-compose exec app composer install
```

### Se precisar regenerar chave da aplicação

```bash
docker-compose exec app php artisan key:generate
```
