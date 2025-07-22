# 🚀 PHP Laravel API - Sistema de Gerenciamento de Usuários, Clientes e Endereços

API REST desenvolvida em PHP com Laravel para o teste técnico da empresa Einstein, implementando um sistema completo de gerenciamento de usuários, clientes e endereços com foco em qualidade de código, arquitetura escalável e boas práticas.

## 🎯 Objetivo

Desenvolver uma API REST robusta e escalável que atenda aos requisitos do teste técnico, demonstrando proficiência em:

-   **Clean Code e princípios SOLID**
-   **Arquitetura MVC do Laravel**
-   **Autenticação JWT**
-   **Tratamento de exceções**
-   **Logs estruturados**
-   **Testes automatizados**
-   **Documentação via Swagger**
-   **Containerização com Docker**

## 🛠️ Tecnologias Utilizadas

### Core Technologies

-   **PHP 8.2+**: Linguagem de programação server-side
-   **Laravel 12**: Framework PHP moderno e robusto
-   **PostgreSQL 16**: Banco de dados relacional robusto e escalável

### Authentication & Security

-   **JWT (tymon/jwt-auth)**: Autenticação baseada em tokens
-   **Laravel Sanctum**: Sistema de autenticação API
-   **Hash**: Criptografia de senhas nativa do Laravel

### Validation & Documentation

-   **Laravel Form Requests**: Validação de dados de entrada
-   **L5-Swagger**: Documentação interativa da API
-   **OpenAPI/Swagger**: Especificação de API

### Testing

-   **PHPUnit**: Framework de testes unitários
-   **Laravel Testing**: Testes de integração HTTP
-   **Faker**: Geração de dados de teste

### Development Tools

-   **Laravel Sail**: Ambiente Docker para desenvolvimento
-   **Laravel Pint**: Formatação de código
-   **Laravel Pail**: Visualização de logs em tempo real

## 🏗️ Arquitetura do Projeto

O projeto segue a arquitetura MVC do Laravel com organização em camadas bem definidas:

```
app/
├── Http/
│   ├── Controllers/     # Camada de controle - endpoints da API
│   ├── Middleware/      # Middlewares personalizados
│   └── Requests/        # Validação de dados de entrada
├── Models/              # Camada de modelo - Eloquent ORM
├── Services/            # Camada de serviços - lógica de negócio
├── Exceptions/          # Tratamento de exceções customizadas
└── Logging/             # Sistema de logs estruturados
```

### Princípios SOLID Aplicados

-   **S** - **Single Responsibility**: Cada classe tem uma responsabilidade única
-   **O** - **Open/Closed**: Extensível sem modificação do código existente
-   **L** - **Liskov Substitution**: Interfaces bem definidas
-   **I** - **Interface Segregation**: Interfaces específicas para cada necessidade
-   **D** - **Dependency Inversion**: Injeção de dependência

## 📊 Modelo de Dados

### Diagrama do Banco de Dados

```sql
users
├── id (PK, autoincrement)
├── name (varchar, obrigatório)
├── email (varchar, unique, obrigatório)
├── password (varchar, obrigatório)
├── created_at (timestamp)
└── updated_at (timestamp)

customers
├── id (PK, autoincrement)
├── name (varchar, obrigatório)
├── email (varchar, unique, obrigatório)
├── cpf (varchar, unique, obrigatório)
├── created_at (timestamp)
└── updated_at (timestamp)

addresses
├── id (PK, autoincrement)
├── customer_id (FK → customers.id)
├── address (varchar, obrigatório)
├── number (varchar, obrigatório)
├── complement (varchar, nullable)
├── zip_code (varchar, obrigatório)
├── created_at (timestamp)
└── updated_at (timestamp)
```

## 🔐 Autenticação e Segurança

### JWT Authentication

-   Tokens JWT para autenticação de usuários
-   Middleware de autenticação em rotas protegidas
-   Validação automática de tokens em requisições
-   Configuração via `tymon/jwt-auth`

### Validação de Dados

-   Form Requests para validação de entrada
-   Validação de CPF único e válido
-   Validação de email único e formato
-   Senhas com mínimo 8 caracteres (letras e números)

### Tratamento de Exceções

-   Sistema global de tratamento de erros
-   Exceções customizadas por tipo de erro
-   Logs estruturados com formatação personalizada

## 📋 Endpoints da API

### 🔑 UserController

Gerencia os usuários do sistema com autenticação JWT.

| Método | Rota           | Descrição                     | Autenticação |
| ------ | -------------- | ----------------------------- | ------------ |
| POST   | `/users`       | Cadastrar novo usuário        | ✅           |
| PATCH  | `/users/{id}`  | Atualizar usuário             | ✅           |
| DELETE | `/users/{id}`  | Deletar usuário               | ✅           |
| POST   | `/users/login` | Realizar login                | ❌           |
| GET    | `/users/{id}`  | Buscar usuário por ID         | ✅           |
| GET    | `/users`       | Listar usuários com paginação | ✅           |

### 👥 CustomerController

Gerencia os clientes da aplicação com autenticação obrigatória.

| Método | Rota              | Descrição                     | Autenticação |
| ------ | ----------------- | ----------------------------- | ------------ |
| POST   | `/customers`      | Cadastrar novo cliente        | ✅           |
| PATCH  | `/customers/{id}` | Atualizar cliente             | ✅           |
| DELETE | `/customers/{id}` | Deletar cliente               | ✅           |
| GET    | `/customers/{id}` | Buscar cliente por ID         | ✅           |
| GET    | `/customers`      | Listar clientes com endereços | ✅           |

### 🏠 CustomerAddressController

Gerencia os endereços dos clientes com autenticação obrigatória.

| Método | Rota                                     | Descrição          | Autenticação |
| ------ | ---------------------------------------- | ------------------ | ------------ |
| POST   | `/customers/{customerId}/addresses`      | Cadastrar endereço | ✅           |
| PATCH  | `/customers/{customerId}/addresses/{id}` | Atualizar endereço | ✅           |
| DELETE | `/customers/{customerId}/addresses/{id}` | Deletar endereço   | ✅           |
| GET    | `/customers/{customerId}/addresses/{id}` | Buscar endereço    | ✅           |
| GET    | `/customers/{customerId}/addresses`      | Listar endereços   | ✅           |

## 🚀 Instalação e Execução

### Pré-requisitos

-   PHP 8.2+
-   Composer
-   Docker e Docker Compose
-   PostgreSQL (opcional para desenvolvimento local)

### 1. Clone o repositório

```bash
git clone <url-do-repositorio>
cd php-api
```

### 2. Instalação das dependências

```bash
composer install
```

### 3. Configuração do ambiente

```bash
# Copie o arquivo de exemplo
cp .env.example .env

# Configure as variáveis de ambiente
DB_CONNECTION=pgsql
DB_HOST=postgres
DB_PORT=5432
DB_DATABASE=testdb
DB_USERNAME=postgres
DB_PASSWORD=12345
JWT_SECRET=sua-chave-secreta-aqui
APP_ENV=local
APP_DEBUG=true
```

### 4. Execução com Docker (Recomendado)

```bash
# Construir e executar todos os serviços
docker-compose up --build

# A API estará disponível em: http://localhost:5002
# Documentação Swagger: http://localhost:5002/api/documentation
```

### 5. Execução local (Desenvolvimento)

```bash
# Gerar chave da aplicação
php artisan key:generate

# Gerar chave JWT
php artisan jwt:secret

# Executar migrações
php artisan migrate

# Executar seeders (dados iniciais)
php artisan db:seed

# Iniciar servidor de desenvolvimento
php artisan serve
```

### 6. Comandos de Desenvolvimento

```bash
# Executar todos os comandos de desenvolvimento
composer run dev

# Formatar código
./vendor/bin/pint

# Executar testes
composer test
```

## 🧪 Testes

### Executar testes

```bash
# Executar todos os testes
php artisan test

# Executar testes específicos
php artisan test --filter=UserControllerTest

# Executar testes com cobertura
php artisan test --coverage
```

### Cobertura de Testes

O projeto possui testes unitários e de integração cobrindo:

-   Controllers (User, Customer, Address)
-   Models
-   Services
-   Middlewares
-   Validações

## 📚 Documentação

### Swagger UI

Acesse a documentação interativa da API:

```
http://localhost:5002/api/documentation
```

### Gerar Documentação

```bash
# Gerar documentação Swagger
php artisan l5-swagger:generate
```

## 🐳 Docker

### Estrutura Docker

-   **Dockerfile**: Configuração da imagem PHP Laravel
-   **docker-compose.yml**: Orquestração dos serviços
-   **docker/php/local.ini**: Configurações PHP personalizadas

### Serviços Docker

-   **Laravel App**: Porta 5002
-   **PostgreSQL**: Porta 5432
-   **Volumes**: Persistência de dados

### Configurações PHP

```ini
upload_max_filesize=40M
post_max_size=40M
memory_limit=512M
max_execution_time=600
max_input_vars=3000
```

### Comandos Docker

```bash
# Construir e executar
docker-compose up --build

# Executar em background
docker-compose up -d

# Parar serviços
docker-compose down

# Visualizar logs
docker-compose logs -f app

# Acessar container
docker-compose exec app bash
```

## 🔧 Configurações Específicas

### JWT Configuration

O projeto utiliza `tymon/jwt-auth` para autenticação JWT com configurações otimizadas:

-   Algoritmo: HS256
-   Tempo de vida do token: Configurável
-   Refresh tokens: Suportado

### Logging

Sistema de logs estruturados com formatação personalizada:

-   Logs de API com middleware dedicado
-   Formatação JSON para logs estruturados
-   Rotação automática de logs

### Database

-   PostgreSQL como banco principal
-   Migrações automáticas no Docker
-   Seeders para dados de teste
-   Factories para geração de dados

## 📝 Estrutura de Resposta da API

### Formato Padrão

```json
{
    "status": 200,
    "message": "Operação realizada com sucesso",
    "data": {
        // Dados da resposta
    }
}
```

### Formato de Erro

```json
{
    "status": 422,
    "message": "Dados inválidos",
    "errors": {
        "field": ["Mensagem de erro"]
    }
}
```

## 🤝 Contribuição

1. Fork o projeto
2. Crie uma branch para sua feature (`git checkout -b feature/AmazingFeature`)
3. Commit suas mudanças (`git commit -m 'Add some AmazingFeature'`)
4. Push para a branch (`git push origin feature/AmazingFeature`)
5. Abra um Pull Request

## 📄 Licença

Este projeto está sob a licença MIT. Veja o arquivo [LICENSE](LICENSE) para mais detalhes.

## 👨‍💻 Autor

**Rafael Dias Soares**

-   GitHub: [@rafaeldiassoares](https://github.com/rafaeldiassoares)
-   LinkedIn: [Rafael Dias Soares](https://linkedin.com/in/rafaeldiassoares)

---

⭐ Se este projeto te ajudou, considere dar uma estrela no repositório!
