# SAT Challenge - Customer Management System

Sistema de gestão de clientes desenvolvido em Laravel, utilizando boas práticas de arquitetura de software e padrões de projeto.

## Tecnologias Utilizadas

-   **PHP 8.2+**
-   **Laravel 12**
-   **MySQL/SQLite**
-   **Laravel Sanctum** (autenticação API)
-   **Vite** (bundler de assets)
-   **AdminLTE 4** (template administrativo)
-   **DataTables** (Bootstrap 5)
-   **Pest PHP** (testes automatizados)

## Arquitetura

O projeto segue uma arquitetura em camadas com separação clara de responsabilidades:

```
app/
├── Domain/                    # Lógica de negócio (agnóstica de framework)
│   ├── Customer/
│   │   ├── DTOs/              # Data Transfer Objects
│   │   ├── Repositories/      # Interfaces e implementações
│   │   └── Services/          # Regras de negócio
│   └── User/
│       ├── DTOs/
│       ├── Repositories/
│       └── Services/
├── Http/
│   ├── Controllers/           # Controladores (orquestração)
│   │   ├── Api/               # Controllers da API RESTful
│   │   └── Auth/              # Controllers de autenticação
│   ├── Requests/              # Form Requests (validação)
│   └── Resources/             # API Resources (transformação)
└── Providers/
    └── RepositoryServiceProvider.php  # Binding de interfaces
```

### Padrões Implementados

-   **Repository Pattern**: Abstração do acesso a dados
-   **Service Layer**: Lógica de negócio isolada
-   **DTO (Data Transfer Object)**: Objetos imutáveis para transferência de dados
-   **API Resources**: Padronização de respostas JSON
-   **Dependency Injection**: Injeção via interfaces

## Requisitos

-   PHP >= 8.2
-   Composer
-   Node.js >= 18
-   MySQL ou SQLite

## Instalação

1. Clone o repositório:

```bash
git clone https://github.com/mrfernandodias/sat-challenge.git
cd sat-challenge
```

2. Instale as dependências PHP:

```bash
composer install
```

3. Instale as dependências JavaScript:

```bash
npm install
```

4. Configure o ambiente:

```bash
cp .env.example .env
php artisan key:generate
```

5. Configure o banco de dados no arquivo `.env`:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=sat_challenge
DB_USERNAME=seu_usuario
DB_PASSWORD=sua_senha
```

6. Execute as migrations e seeders:

```bash
php artisan migrate --seed
```

7. Compile os assets:

```bash
npm run build
```

8. Configure as permissões (Linux/Mac):

```bash
chmod -R 775 storage bootstrap/cache
```

9. Inicie o servidor:

```bash
php artisan serve
```

Acesse: http://localhost:8000

### Credenciais Padrão

Após executar o seeder, use as seguintes credenciais para acessar o sistema:

-   **Email:** `admin@admin.com`
-   **Senha:** `admin`

## Instalação com Docker (Alternativa)

Se preferir usar Docker, siga estes passos:

1. Clone o repositório:

```bash
git clone https://github.com/mrfernandodias/sat-challenge.git
cd sat-challenge
```

2. Copie o arquivo de ambiente para Docker:

```bash
cp .env.docker .env
```

3. Suba os containers:

```bash
docker-compose up -d --build
```

4. Execute os comandos de setup:

```bash
docker-compose exec app php artisan key:generate
docker-compose exec app php artisan migrate --seed
```

5. Acesse: http://localhost:8000

### Comandos úteis do Docker

```bash
# Ver logs
docker-compose logs -f

# Parar containers
docker-compose down

# Executar testes
docker-compose exec app php artisan test

# Acessar o container
docker-compose exec app bash
```

## Desenvolvimento

Para desenvolvimento com hot reload:

```bash
npm run dev
```

## Testes

O projeto utiliza Pest PHP para testes automatizados.

### Executar todos os testes:

```bash
php artisan test
```

### Executar testes com cobertura:

```bash
php artisan test --coverage
```

### Estrutura de testes:

```
tests/
├── Feature/
│   ├── Api/                   # Testes da API RESTful
│   │   ├── AuthTest.php
│   │   └── CustomerApiTest.php
│   ├── AuthTest.php           # Testes de autenticação web
│   ├── UserTest.php           # Testes do CRUD de usuários
│   └── Customer/              # Testes Web de clientes
│       ├── CreateCustomerTest.php
│       ├── ListCustomerTest.php
│       ├── UpdateCustomerTest.php
│       └── DeleteCustomerTest.php
└── Unit/Customer/             # Testes unitários
    ├── CustomerDTOTest.php
    └── CustomerResourceTest.php
```

## Autenticação

### Web (Session)

O sistema web utiliza autenticação baseada em sessão. Todas as rotas são protegidas e requerem login.

| Rota         | Descrição       |
| ------------ | --------------- |
| GET /login   | Página de login |
| POST /login  | Processar login |
| POST /logout | Encerrar sessão |

### API (Token - Sanctum)

A API utiliza tokens de autenticação via Laravel Sanctum.

## Módulos

### Dashboard

O dashboard exibe métricas sobre os clientes cadastrados:

-   **Total de Clientes** - Quantidade total de registros
-   **Novos este Mês** - Clientes cadastrados no mês atual
-   **Novos Hoje** - Clientes cadastrados no dia atual
-   **Estados Atendidos** - Quantidade de estados distintos
-   **Gráfico de Cadastros por Mês** - Evolução dos últimos 6 meses
-   **Gráfico de Clientes por Estado** - Distribuição geográfica (top 10)
-   **Últimos Cadastros** - Lista dos 5 clientes mais recentes

### Gerenciamento de Clientes

| Método | Endpoint          | Descrição                    |
| ------ | ----------------- | ---------------------------- |
| GET    | `/customers`      | Página de listagem           |
| GET    | `/customers/data` | Dados para DataTables (JSON) |
| POST   | `/customers`      | Criar cliente                |
| PUT    | `/customers/{id}` | Atualizar cliente            |
| DELETE | `/customers/{id}` | Excluir cliente              |

### Gerenciamento de Usuários

| Método | Endpoint      | Descrição                    |
| ------ | ------------- | ---------------------------- |
| GET    | `/users`      | Página de listagem           |
| GET    | `/users/data` | Dados para DataTables (JSON) |
| POST   | `/users`      | Criar usuário                |
| PUT    | `/users/{id}` | Atualizar usuário            |
| DELETE | `/users/{id}` | Excluir usuário              |

**Obs:** Não é permitido excluir o próprio usuário logado.

## API RESTful (Sanctum)

Endpoints protegidos por autenticação via token.

#### Autenticação

| Método | Endpoint          | Descrição               | Auth |
| ------ | ----------------- | ----------------------- | ---- |
| POST   | `/api/login`      | Gerar token de acesso   | Não  |
| POST   | `/api/logout`     | Revogar token atual     | Sim  |
| POST   | `/api/logout-all` | Revogar todos os tokens | Sim  |
| GET    | `/api/user`       | Dados do usuário logado | Sim  |

#### Customers

| Método | Endpoint              | Descrição         | Auth |
| ------ | --------------------- | ----------------- | ---- |
| GET    | `/api/customers`      | Listar clientes   | Sim  |
| GET    | `/api/customers/{id}` | Detalhes cliente  | Sim  |
| POST   | `/api/customers`      | Criar cliente     | Sim  |
| PUT    | `/api/customers/{id}` | Atualizar cliente | Sim  |
| DELETE | `/api/customers/{id}` | Excluir cliente   | Sim  |

#### Autenticação na API

1. Faça login para obter o token:

```bash
curl -X POST http://localhost:8000/api/login \
  -H "Content-Type: application/json" \
  -d '{"email": "user@example.com", "password": "password", "device_name": "curl"}'
```

Resposta:

```json
{
    "message": "Login realizado com sucesso.",
    "token": "1|abc123...",
    "user": { "id": 1, "name": "User", "email": "user@example.com" }
}
```

2. Use o token nas requisições:

```bash
curl -X GET http://localhost:8000/api/customers \
  -H "Authorization: Bearer 1|abc123..."
```

### Exemplo de Request (POST /customers)

```json
{
    "name": "João da Silva",
    "phone": "(11) 99999-9999",
    "cpf": "123.456.789-00",
    "email": "joao@example.com",
    "cep": "01310-100",
    "street": "Avenida Paulista",
    "neighborhood": "Bela Vista",
    "number": "1000",
    "complement": "Sala 101",
    "city": "São Paulo",
    "state": "SP"
}
```

### Exemplo de Response

```json
{
    "success": true,
    "message": "Cliente criado com sucesso.",
    "customer": {
        "id": 1,
        "name": "João da Silva",
        "email": "joao@example.com",
        "phone": "(11) 99999-9999",
        "cpf": "123.456.789-00",
        "city": "São Paulo",
        "state": "SP",
        "full_address": "Avenida Paulista, 1000, Sala 101, Bela Vista, São Paulo, SP",
        "created_at": "2026-01-12T10:30:00.000000Z",
        "updated_at": "2026-01-12T10:30:00.000000Z"
    }
}
```

## Testando a API com Postman

Uma collection do Postman está disponível para facilitar os testes da API.

### Importando a Collection

1. Abra o Postman
2. Clique em **Import** (ou `Ctrl+O`)
3. Selecione o arquivo `docs/postman/SAT-API.postman_collection.json`
4. A collection "SAT API - Customer Management" será adicionada

### Como usar

1. **Execute o request "Login"** primeiro para autenticar
    - O token será salvo automaticamente na variável `token`
2. Use os demais endpoints normalmente - a autenticação já estará configurada

### Variáveis da Collection

| Variável   | Valor Padrão                | Descrição                                                     |
| ---------- | --------------------------- | ------------------------------------------------------------- |
| `base_url` | `http://localhost:8000/api` | URL base da API                                               |
| `token`    | (vazio)                     | Token de autenticação (preenchido automaticamente após login) |

### Credenciais para teste

-   **Email:** `admin@admin.com`
-   **Senha:** `admin`

## Estrutura do Projeto

```
sat-challenge/
├── app/
│   ├── Domain/Customer/       # Domínio de clientes
│   ├── Http/Controllers/      # Controladores
│   ├── Http/Requests/         # Validações
│   ├── Http/Resources/        # Transformação de dados
│   ├── Models/                # Eloquent Models
│   └── Providers/             # Service Providers
├── database/
│   ├── factories/             # Factories para testes
│   └── migrations/            # Migrações do banco
├── resources/
│   ├── css/                   # Estilos customizados
│   ├── js/                    # JavaScript (módulos)
│   │   ├── pages/             # Scripts específicos por página
│   │   └── services/          # Serviços reutilizáveis
│   └── views/                 # Blade templates
├── routes/
│   ├── web.php                # Rotas Web
│   └── api.php                # Rotas API RESTful
└── tests/                     # Testes automatizados
```

## Serviços JavaScript

Serviços modulares e reutilizáveis localizados em `resources/js/services/`:

### CEP Service

Integração com a API ViaCEP para auto-preenchimento de endereço.

```javascript
import { setupCepAutocomplete } from "./services/cep-service";

setupCepAutocomplete(cepInput, {
    street: document.getElementById("street"),
    neighborhood: document.getElementById("neighborhood"),
    city: document.getElementById("city"),
    state: document.getElementById("state"),
});
```

### Mask Service

Máscaras de input para formatação de campos.

```javascript
import { createMask, masks } from "./services/mask-service";

createMask(document.getElementById("cep"), masks.cep); // 12345-678
createMask(document.getElementById("cpf"), masks.cpf); // 123.456.789-00
createMask(document.getElementById("phone"), masks.phone); // (11) 99999-9999
```

## Licença

Este projeto está sob a licença MIT.
