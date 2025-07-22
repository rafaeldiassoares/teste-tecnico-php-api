#!/bin/bash

# Aguardar o banco de dados estar pronto
echo "Aguardando banco de dados..."
while ! nc -z postgres 5432; do
  sleep 1
done
echo "Banco de dados está pronto!"

# Gerar chave da aplicação se não existir
if [ ! -f .env ]; then
    cp .env.example .env
fi

# Gerar APP_KEY se não existir
if ! grep -q "APP_KEY=base64:" .env; then
    php artisan key:generate --no-interaction
fi

# Limpar cache de configuração
php artisan config:clear
php artisan cache:clear

# Executar migrações
php artisan migrate --force

# Iniciar o servidor
php artisan serve --host=0.0.0.0 --port=8000
