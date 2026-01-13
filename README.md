# SAT Challenge - Customer Management System

Sistema de gestão de clientes desenvolvido em Laravel, utilizando boas práticas de arquitetura de software e padrões de projeto.

## Tecnologias Utilizadas

-   **PHP 8.2+**
-   **Laravel 12**
-   **MySQL/SQLite**
-   **Vite** (bundler de assets)
-   **AdminLTE 4** (template administrativo)
-   **DataTables** (Bootstrap 5)
-   **Pest PHP** (testes automatizados)

## Arquitetura

O projeto segue uma arquitetura em camadas com separação clara de responsabilidades:

```
app/
├── Domain/                    # Lógica de negócio (agnóstica de framework)
│   └── Customer/
│       ├── DTOs/              # Data Transfer Objects
│       ├── Repositories/      # Interfaces e implementações
│       └── Services/          # Regras de negócio
├── Http/
│   ├── Controllers/           # Controladores (orquestração)
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

6. Execute as migrations:

```bash
php artisan migrate
```

7. Compile os assets:

```bash
npm run build
```

8. Inicie o servidor:

```bash
php artisan serve
```

Acesse: http://localhost:8000

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
├── Feature/Customer/          # Testes de integração
│   ├── CreateCustomerTest.php
│   ├── ListCustomerTest.php
│   ├── UpdateCustomerTest.php
│   └── DeleteCustomerTest.php
└── Unit/Customer/             # Testes unitários
    ├── CustomerDTOTest.php
    └── CustomerResourceTest.php
```

## API Endpoints

### Customers

| Método | Endpoint          | Descrição                    |
| ------ | ----------------- | ---------------------------- |
| GET    | `/customers`      | Página de listagem           |
| GET    | `/customers/data` | Dados para DataTables (JSON) |
| POST   | `/customers`      | Criar cliente                |
| PUT    | `/customers/{id}` | Atualizar cliente            |
| DELETE | `/customers/{id}` | Excluir cliente              |

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
│   └── web.php                # Rotas da aplicação
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
