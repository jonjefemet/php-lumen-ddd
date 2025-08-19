<div align="center">

[![PHP](https://img.shields.io/badge/PHP-8.4-777BB4?style=for-the-badge&logo=php&logoColor=white)](https://php.net)
[![Lumen](https://img.shields.io/badge/Lumen-FF2D20?style=for-the-badge&logo=laravel&logoColor=white)](https://lumen.laravel.com)
[![Docker](https://img.shields.io/badge/Docker-2496ED?style=for-the-badge&logo=docker&logoColor=white)](https://docker.com)
[![MongoDB](https://img.shields.io/badge/MongoDB-47A248?style=for-the-badge&logo=mongodb&logoColor=white)](https://mongodb.com)
[![Symfony](https://img.shields.io/badge/Symfony-000000?style=for-the-badge&logo=symfony&logoColor=white)](https://symfony.com)

[![Composer](https://img.shields.io/badge/Composer-885630?style=for-the-badge&logo=composer&logoColor=white)](https://getcomposer.org)
[![PHPUnit](https://img.shields.io/badge/PHPUnit-366488?style=for-the-badge&logo=php&logoColor=white)](https://phpunit.de)
[![Doctrine ORM](https://img.shields.io/badge/Doctrine%20ORM-FC6A31?style=for-the-badge&logo=doctrine&logoColor=white)](https://doctrine-project.org)

<br><br>

<img src="assets/home.png" alt="Architecture Overview" width="800">

</div>

Un monorepo completo implementando una arquitectura de microservicios usando **PHP 8.4**, **Domain-Driven Design (DDD)**, **CQRS**, **Event Sourcing** y las mejores prácticas de desarrollo con arquitectura hexagonal.

## 🏗️ Arquitectura

### 📁 Estructura del Proyecto

```
mono/
├── apps/                          # Aplicaciones (Entry Points)
│   ├── bootstrap.php              # Bootstrap compartido
│   ├── mooc/
│   │   └── backend/               # API Backend de MOOC
│   │       ├── public/index.php   # Entry point
│   │       └── src/               # Controllers y Kernel
│   ├── backoffice/
│   │   ├── backend/               # API Backend de Backoffice
│   │   └── frontend/              # Frontend Web de Backoffice
├── src/                           # Dominio y Lógica de Negocio
│   ├── Shared/                    # Código compartido
│   │   ├── Domain/                # Interfaces y contratos
│   │   └── Infrastructure/        # Implementaciones
│   ├── Mooc/                      # Bounded Context MOOC
│   │   ├── Courses/               # Agregado Courses
│   │   ├── Students/              # Agregado Students
│   │   └── Shared/                # Compartido del contexto
│   └── Backoffice/                # Bounded Context Backoffice
├── tests/                         # Tests organizados por contexto
├── etc/                           # Configuraciones externas
│   ├── databases/                 # Scripts SQL
│   └── infrastructure/            # Configs de infraestructura
└── var/                           # Archivos temporales y logs
```

### 🎯 Bounded Contexts

1. **Auth**: Sistema de autenticación y autorización
   - Users: Registro, login, gestión de usuarios
   - Tokens: JWT y gestión de sesiones
   - Permissions: Roles y permisos

2. **Backoffice**: Administración del sistema
   - Users: Vista administrativa de usuarios
   - Analytics: Métricas y reportes
   - Monitoring: Logs y monitoreo del sistema

### 🧩 Patrones Implementados

- **Domain-Driven Design (DDD)**
- **CQRS** (Command Query Responsibility Segregation)
- **Event Sourcing**
- **Hexagonal Architecture**
- **Command/Query Bus Pattern**
- **Repository Pattern**
- **Value Objects**
- **Aggregate Roots**

## 🚀 Inicio Rápido

### Prerrequisitos

- Docker & Docker Compose
- Make (opcional)

### 🔧 Instalación

```bash
# 1. Clonar el repositorio
git clone <repository>
cd mono

# 2. Levantar servicios
make start

# 3. Instalar dependencias
make install

# 4. Crear bases de datos
make db-create
```

### 🌐 Endpoints Disponibles

- **Auth Backend**: http://localhost:8030
- **Backoffice Backend**: http://localhost:8040  
- **Backoffice Frontend**: http://localhost:8041
- **MongoDB**: localhost:27017 (`admin`/`secret`)
- **RabbitMQ Management**: http://localhost:15672 (`codelytv`/`c0d3ly`)

## 🛠️ Comandos Disponibles

### 🐳 Docker & Servicios

```bash
make start          # Iniciar todos los servicios
make stop           # Detener servicios
make restart        # Reiniciar servicios
make status         # Ver estado de servicios
make logs           # Ver logs de todos los servicios
make logs-auth      # Ver logs de auth backend
make health         # Check de salud de servicios
```

### 🧪 Testing

```bash
make test                    # Ejecutar todos los tests
make test-unit              # Tests unitarios
make test-integration       # Tests de integración
make test-acceptance        # Tests de aceptación
```

### 🔍 Calidad de Código

```bash
make static-analysis        # Análisis estático completo
make stan                   # PHPStan
make psalm                  # Psalm
make ecs                    # Easy Coding Standard
make rector                 # Rector
make phpmd                  # PHP Mess Detector
```

### 🗄️ Base de Datos

```bash
make db-create              # Inicializar MongoDB
make shell-mongodb          # Acceder a MongoDB
```

### 🔧 Desarrollo

```bash
make shell-auth                    # Shell del backend auth
make shell-backoffice-backend      # Shell del backend backoffice
make install                       # Instalar dependencias
```

## 📚 API Endpoints

### 🔐 Auth Backend

```bash
# Health Check
GET /health

# Registro de Usuario
POST /api/auth/register
Content-Type: application/json
{
    "id": "uuid",
    "email": "user@finger.com",
    "password": "Password123",
    "name": "User Name"
}

# Login de Usuario
POST /api/auth/login
Content-Type: application/json
{
    "email": "user@finger.com",
    "password": "Password123"
}
```

### 🏢 Backoffice Backend

```bash
# Health Check
GET /health

# Obtener Usuarios
GET /api/users

# Obtener Analytics
GET /api/analytics
```

## 🎯 Ejemplos de Uso

### Registrar un Usuario

```bash
make register-user
# O manualmente:
curl -X POST http://localhost:8030/api/auth/register \
  -H "Content-Type: application/json" \
  -d '{"id": "123e4567-e89b-12d3-a456-426614174000", "email": "test@finger.com", "password": "Password123", "name": "Test User"}'
```

### Hacer Login

```bash
make login-user
# O manualmente:
curl -X POST http://localhost:8030/api/auth/login \
  -H "Content-Type: application/json" \
  -d '{"email": "test@finger.com", "password": "Password123"}'
```

### Verificar Salud de Servicios

```bash
make ping-auth
# Respuesta esperada:
# {"status":"ok","service":"auth-backend","timestamp":"2024-01-01T12:00:00+00:00"}
```

## 🏛️ Arquitectura DDD Detallada

### 📋 Value Objects

```php
// Ejemplo: UserId
final class UserId extends Uuid
{
    // Validación automática de UUID
}

// Ejemplo: UserEmail
final class UserEmail extends StringValueObject
{
    public function __construct(string $value)
    {
        $this->ensureIsValidEmail($value);
        parent::__construct(strtolower(trim($value)));
    }
}
```

### 🏗️ Agregados

```php
final class User extends AggregateRoot
{
    public static function create(
        UserId $id, 
        UserEmail $email, 
        UserPassword $password,
        UserName $name
    ): self {
        $user = new self($id, $email, $password, $name);
        
        $user->record(new UserWasCreated(
            $id->value(),
            $email->value(),
            $name->value(),
            $user->createdAt()
        ));
        
        return $user;
    }
}
```

### ⚡ Command Bus

```php
// Command
final class CreateUserCommand implements Command
{
    public function __construct(
        private string $id,
        private string $email,
        private string $password,
        private string $name
    ) {}
}

// Handler
final class CreateUserCommandHandler implements CommandHandler
{
    public function __invoke(CreateUserCommand $command): void
    {
        $this->creator->__invoke(
            $command->id(),
            $command->email(),
            $command->password(),
            $command->name()
        );
    }
}
```

### 📡 Event Bus

```php
// Domain Event
final class UserWasCreated extends DomainEvent
{
    public static function eventName(): string
    {
        return 'user.was_created';
    }
    
    public function toPrimitives(): array
    {
        return [
            'email' => $this->email,
            'name' => $this->name,
            'created_at' => $this->createdAt->format('Y-m-d H:i:s'),
        ];
    }
}
```

## 🔧 Tecnologías

- **PHP 8.4** - Última versión con todas las mejoras
- **Lumen** - Framework minimalista de Laravel
- **Docker** - Containerización
- **MongoDB 7.0** - Base de datos NoSQL
- **RabbitMQ** - Message broker para eventos
- **Elasticsearch** - Motor de búsqueda
- **Symfony Messenger** - Command/Query/Event Bus
- **PHPUnit** - Testing framework
- **PHPStan** - Análisis estático
- **Psalm** - Análisis de tipos
- **Rector** - Refactoring automático

## 🧪 Testing

### Estructura de Tests

```
tests/
├── Shared/                     # Tests compartidos
├── Auth/
│   ├── Users/
│   │   ├── Application/        # Tests de casos de uso
│   │   ├── Domain/             # Tests de dominio
│   │   └── Infrastructure/     # Tests de infraestructura
└── Backoffice/
```

### Ejecutar Tests

```bash
# Todos los tests
composer test

# Solo unitarios
composer test:unit

# Solo integración
composer test:integration

# Con coverage
composer test -- --coverage-html var/coverage
```

## 📊 Calidad de Código

### Herramientas Configuradas

- **PHPStan** (Nivel 8): Análisis estático máximo
- **Psalm**: Verificación de tipos
- **ECS**: Estándares de código
- **Rector**: Modernización automática
- **PHPMD**: Detección de code smells

### Ejecutar Análisis

```bash
# Análisis completo
composer static:analysis

# Individual
composer stan     # PHPStan
composer psalm    # Psalm
composer ecs      # Easy Coding Standard
composer rector   # Rector (no ejecuta, solo muestra)
composer phpmd    # PHP Mess Detector
```

## 🔒 Mejores Prácticas

### ✅ Código

- Strict types habilitado
- Value Objects para primitivas
- Inmutabilidad por defecto
- Interfaces para contratos
- Dependency Injection
- Single Responsibility Principle

### ✅ Testing

- Test unitarios para lógica de dominio
- Test de integración para infraestructura
- Test de aceptación para casos de uso completos
- Mocks para dependencias externas

### ✅ Arquitectura

- Separación clara de capas
- Bounded Contexts bien definidos
- Event-driven communication
- CQRS para separar lecturas/escrituras

## 🚀 Escalabilidad

### Horizontal

- Microservicios independientes
- Comunicación asíncrona via eventos
- Bases de datos separadas por contexto
- Load balancing ready

### Vertical

- Caché con Redis
- Optimizaciones de consultas
- Connection pooling
- Compression habilitada

## 🤝 Contribución

1. Fork del proyecto
2. Crear branch: `git checkout -b feature/nueva-funcionalidad`
3. Ejecutar tests: `make test`
4. Ejecutar análisis: `make static-analysis`
5. Commit: `git commit -am 'Añadir nueva funcionalidad'`
6. Push: `git push origin feature/nueva-funcionalidad`
7. Pull Request

## 📝 Licencia

MIT License - Ver archivo `LICENSE` para más detalles.

---

## 💡 Arquitectura Profesional

Este proyecto implementa las mejores prácticas de **Domain-Driven Design**, **CQRS**, **Event Sourcing** y **Arquitectura Hexagonal** adaptado para microservicios modernos con PHP 8.4 y Lumen.

**¡Happy Coding! 🎉**

```
Nunca es tarde para no hacer nada

⣿⣿⣿⣿⣿⣿⣿⣿⣿⣿⣿⣿⣿⣿⣿⣿⣿⣿⣿⣿⣿⣿⣿⣿⣿⣿⣿⣿⣿⣿⡿⠿⠿⢿⣿⣿⣿⣿⣿⣿⣿⣿⣿⣿⣿⣿⣿⣿⣿⣿⣿⣿⣿⣿⣿⣿⣿⣿⣿⣿⣿⣿⣿⣿⣿⣿
⣿⣿⣿⣿⣿⣿⣿⣿⣿⣿⣿⣿⣿⣿⣿⣿⣿⣿⣿⣿⣿⣿⣿⣿⣿⣿⠿⠛⠁⠀⠀⠀⠀⠀⠀⠈⠉⠁⠀⠀⠀⠀⠀⠈⠛⠛⡿⠿⣿⣿⣿⣿⣿⣿⣿⣿⣿⣿⣿⣿⣿⣿⣿⣿⣿⣿
⣿⣿⣿⣿⣿⣿⣿⣿⣿⣿⣿⣿⣿⣿⣿⣿⣿⣿⣿⣿⠿⠿⠿⠿⠋⠀⠀⡀⠐⡈⠄⠡⠈⠄⡁⠂⠄⡀⢀⠈⠠⠁⢂⠡⠀⠄⡀⠀⠀⠋⠟⢿⣿⣿⣿⣿⣿⣿⣿⣿⣿⣿⣿⣿⣿⣿
⣿⣿⣿⣿⣿⣿⣿⣿⣿⣿⣿⣿⣿⣿⣿⠿⠛⠋⠁⠀⠀⡀⢀⠀⢀⠐⠠⢀⠡⠀⢂⠁⠌⠠⠐⢈⠠⢀⠂⠠⠀⠄⠂⠠⢈⠐⢀⠁⢂⠀⡀⠀⠙⠻⣿⣿⣿⣿⣿⣿⣿⣿⣿⣿⣿⣿
⣿⣿⣿⣿⣿⣿⣿⣿⣿⣿⣿⣿⡿⠋⠁⠀⢀⠠⠐⠈⠄⡐⠠⠐⠠⢈⠐⠠⢀⠁⢂⠈⡐⠠⠁⢂⠀⠂⠄⠡⢈⠀⠂⠄⠠⠈⠠⢈⠠⠐⢀⠐⡀⠀⠂⠻⣿⣿⣿⣿⣿⣿⣿⣿⣿⣿
⣿⣿⣿⣿⣿⣿⣿⣿⣿⣿⠿⠁⠀⠀⠄⡈⠄⡐⠈⡐⠠⠐⢀⠁⢂⠀⠐⢀⠂⡈⠄⡐⠠⢀⠁⢂⠈⠐⢈⠠⠀⠌⠐⡈⠄⠈⢀⠂⠄⡈⢀⠂⠠⢁⠀⠀⠈⢿⣿⣿⣿⣿⣿⣿⣿⣿
⣿⣿⣿⣿⣿⣿⣿⣿⠟⠁⠀⠀⠀⠁⠂⠀⠂⠀⠁⠀⠐⠈⠀⠀⠂⠀⠀⠀⠐⠀⠐⠀⠐⠀⠈⠀⠀⠁⠀⠀⠀⠈⠐⠀⠀⠁⠀⠀⠂⠀⠀⠂⠁⠀⠈⠀⠀⠀⠻⣿⣿⣿⣿⣿⣿⣿
⣿⣿⣿⣿⣿⣿⣿⠏⠀⠀⡀⠀⠄⠠⠀⠀⠄⡀⠀⠠⠀⠄⡀⢀⠀⠀⠄⠰⣄⠀⠀⠄⡀⠀⡀⠀⢀⠀⠄⡀⠠⠀⠀⠀⠀⠠⠀⠀⠀⡀⠄⠀⡀⠠⢀⠠⠀⡀⠀⣹⣿⣿⣿⣿⣿⣿
⣿⣿⣿⣿⣿⣿⠫⠀⢀⠐⡀⢁⠂⠄⠡⠈⠄⠠⠁⢂⠡⠀⠀⠄⠂⠀⠄⠀⣬⣤⡀⠀⠐⡀⠀⠌⡀⠌⢀⠐⡀⠂⠀⠀⡀⠀⠄⡀⠀⠀⠠⠁⠠⢁⠠⠀⠄⠐⠀⠐⢻⣿⣿⣿⣿⣿
⣿⣿⣿⣿⣿⠃⠀⢀⠂⠄⡐⠀⠂⠌⢀⠡⠈⠄⢁⠂⠠⠁⠀⡀⠄⠀⢀⠂⣿⣟⣷⡀⠠⢀⠀⠀⡐⢀⠂⡐⠠⠐⠀⠀⠀⢁⠂⢀⠀⠐⠀⠀⠀⠂⡐⠈⡀⠀⢈⠀⠈⣿⣿⣿⣿⣿
⣿⣿⣿⣿⠃⠀⢀⠂⠄⢂⠠⠁⠈⠀⠂⠠⢈⠐⡀⢂⠀⠀⡐⠠⠀⠀⠠⠀⣿⣯⣟⣷⠀⠀⠄⠀⠀⠄⠂⠐⡀⢂⠀⡄⠀⠂⡈⠄⠀⡀⠈⠀⠠⠁⠄⢂⠐⡀⠀⠀⠀⣿⣿⣿⣿⣿
⣿⣿⣿⡇⠀⠀⠂⠄⡈⠀⠀⠠⢀⠡⠀⠐⡀⠂⠄⠂⠀⠠⠐⠠⢀⡇⠀⢠⣿⣷⣻⣯⡇⠀⠀⡀⠈⠠⠈⡐⠠⠀⢀⢃⠀⠀⠐⢀⣼⣇⠀⠁⠠⢈⠐⡀⠂⠄⡀⠄⠀⣿⣿⣿⣿⣿
⣿⣿⣿⠀⠀⢈⠐⠠⠀⠠⢈⠐⡀⢀⠀⠂⠄⡁⠂⠀⠄⡁⠂⢡⠾⠀⠀⠉⠉⠈⠉⠓⢿⠀⢀⡇⠀⢁⣤⣤⣀⢀⣾⣼⡠⠀⠀⠈⠙⠻⠀⠀⠐⡀⠂⠐⡈⠠⠀⠀⠀⣿⣿⣿⣿⣿
⣿⣿⡇⠀⠀⢂⠈⠀⢀⠂⠄⠂⡀⠂⡈⠐⠠⠐⠀⡐⠀⢀⠄⢀⠀⠀⠀⠀⠀⠰⠶⠶⢦⣠⣾⢷⣴⢿⣯⣟⡿⣟⣿⠊⡠⠀⠀⠀⠀⢀⡀⠀⢁⠠⠁⠂⠄⠁⠀⠀⢰⣿⣿⣿⣿⣿
⣿⣿⡇⠀⠀⠂⠌⠀⡀⠂⠌⠐⡀⠡⢀⠁⠀⠠⢐⣠⡶⠁⣰⡏⠀⠀⠀⠠⠀⠀⠀⠀⢸⢿⣽⡿⣾⣟⣷⢿⣻⡿⣽⡆⡇⠀⠀⠀⡀⠈⠉⠀⠀⠠⢈⠐⢈⠀⢀⣐⣼⣿⣿⣿⣿⣿
⣿⣿⡇⠀⠀⠡⢀⠀⠄⠁⢂⠁⠀⠂⠀⢀⠂⢀⣾⣿⡁⢰⣻⡇⠀⠀⠀⠁⡀⠁⠀⠀⢸⣿⢯⣿⢷⣻⣯⡿⣯⣿⢷⡇⡇⠀⠀⠀⠄⠀⠀⠸⠀⠐⡀⠈⠀⠀⠚⢿⣿⣿⣿⣿⣿⣿
⣿⣿⡇⠀⠀⠡⠀⠄⠀⠈⠀⠀⠠⠐⠈⡀⠄⢼⣿⣳⡇⢼⣿⡇⠀⢠⣤⣁⠀⣰⡿⠀⣼⣟⣯⡿⣯⣟⡾⣽⡷⣯⢿⣏⢿⠀⠸⣦⣤⣴⠂⣸⣄⠀⠠⠁⠂⠄⡀⠀⠙⣿⣿⣿⣿⣿
⣿⣿⣿⠀⠀⠁⠌⠠⠁⠌⠠⠁⠂⢁⠂⠄⠀⠀⠈⠻⣥⠘⢿⣿⣄⠈⠛⠿⠿⠛⢁⣴⢿⡽⣷⣟⡷⣯⢿⣽⠻⣯⣟⣯⣯⣳⣀⠈⠉⣁⣴⡿⣿⢦⠀⠈⡐⠠⠐⡀⠀⠸⣿⣿⣿⣿
⣿⣿⣿⠀⠀⠈⠄⡁⠂⠈⠄⠂⢁⠀⢂⠈⠄⢈⠐⠀⠘⣧⣾⢿⣭⡷⣦⣤⣤⣶⣟⣯⣿⣻⣽⡾⣽⣏⡿⣞⣦⢿⡽⣾⡽⣯⢿⣿⣻⣯⢷⣻⡽⣟⡆⠀⠐⠠⠁⡐⠀⠀⠙⠻⣿⣿
⣿⣿⡇⠀⠀⡁⢂⠐⠈⠄⢂⠈⠠⠐⡀⠌⢀⠂⡈⠐⡀⢸⣯⡿⣷⣻⢷⣯⣷⣻⡾⣯⢷⣟⣷⢿⣳⣯⢿⣽⡾⣿⣽⣳⡿⣽⡿⣞⣷⣯⢿⣳⣟⣯⣧⠀⠀⡁⢂⠐⠀⠀⢠⠀⠈⢿
⣿⣿⠁⠀⠠⠐⡀⠌⠐⡀⢂⠈⡐⠠⠐⢀⠂⡐⢀⠡⠀⠀⣿⣻⣽⣯⣿⣳⣿⣳⣟⣯⣿⣻⡾⡟⠯⠟⠿⠾⠽⠷⢿⡽⣟⣯⣿⢿⣽⡾⣟⣯⢿⣽⠯⠀⢀⠐⡀⠂⠄⠀⢿⠃⠀⠘
⣿⡟⠀⠀⠀⠁⠀⠂⠁⠀⠂⠐⠀⠂⠁⠂⠀⠐⠀⠀⠈⠀⣿⢏⣷⢿⡞⣿⢳⣿⣹⢻⢾⣇⠀⠀⠀⠀⠀⠀⠀⠀⣀⢿⢻⣹⡾⡟⣏⡿⣿⡹⡟⡏⠃⠀⠀⠐⠀⠂⠀⠀⠀⠀⠀⠀
⣿⠁⠀⠀⡀⠠⢀⠀⠀⠄⠀⡀⠄⠀⢀⠀⠄⡀⠄⢀⠀⢰⣿⣎⡿⣾⡼⣧⡿⣧⡿⣏⣾⣼⡀⠀⠀⠀⠄⠠⠀⢠⣿⡾⣏⣿⣱⣿⣹⢷⣧⣷⠿⠁⠀⢀⠀⠀⠀⠀⠀⠀⠀⠀⢀⣶
⡏⠀⠀⠂⠄⡁⠄⠂⡁⠌⠐⠀⠄⡁⠂⠀⠂⠄⠐⠀⢂⠀⠻⣾⢿⠍⣟⡷⣟⡿⣽⢿⣽⡾⣷⣤⡀⠁⠈⠄⣠⣿⢷⣿⣻⡽⣟⣾⡽⣿⠾⠙⠀⢀⠐⡀⢂⠐⠠⠀⠄⠀⣠⣾⣿⣿
⣧⠀⠀⡁⠂⠄⢂⠐⡀⠐⠈⡀⠂⢀⠂⠈⠐⢈⠠⠀⠂⠠⠀⠈⢁⣴⡾⣿⣽⣻⣽⣻⢾⡽⣟⣾⣻⢷⣶⣾⣟⣯⡿⣞⡷⣿⣻⡽⠛⠉⠀⢀⠐⡀⢂⠐⠠⠈⡀⠁⣠⣾⣿⣿⣿⣿
⡇⠀⠠⢀⠁⠐⡀⢂⠠⠁⢂⠀⠡⠀⠂⠄⠈⠠⠐⠀⠐⠀⠀⠀⠈⠛⠹⠟⣾⣳⣯⣟⣯⡿⣽⡷⣯⣿⢷⣻⣾⡽⠻⠙⠋⠁⠀⠀⡀⠄⠂⠄⠂⡐⠀⠀⠁⠀⢲⣿⣿⣿⣿⣿⣿⣿
⣿⡄⠀⠀⠀⠂⠀⠄⠂⡈⠄⡈⠄⠡⢈⠠⢀⠀⠈⠐⠠⠀⠄⠀⢀⣤⣶⣶⣦⡀⢩⣽⣳⣿⣻⣽⣩⠨⣭⣤⣤⡆⠀⠀⠄⠂⢁⠂⠐⠠⢈⠐⠠⠀⠐⣸⣶⣾⣿⣿⣿⣿⣿⣿⣿⣿
⣿⣿⣧⣤⡀⠀⠀⠀⠀⠐⠠⠀⠄⠁⡀⠂⠄⠂⠌⠀⠄⠀⠀⠀⠀⠙⢿⣿⣿⣿⣦⣈⠻⣞⣯⣟⠇⣼⣿⣿⡿⠁⠀⠀⠀⠈⠠⠈⠄⡁⠀⠂⢁⢀⣼⣿⣿⣿⣿⣿⣿⣿⣿⣿⣿⣿
⣿⣿⣿⣿⣿⣶⣶⣾⣶⣤⣤⣤⠂⠀⠀⠈⠀⠐⠈⠄⠀⣿⡀⠀⠀⠀⠀⠙⠿⣿⣿⣿⣷⣤⡉⠋⠰⢿⣿⡿⠃⠀⠀⠀⠀⣷⣄⠈⠀⠀⣠⣴⣾⣿⣿⣿⣿⣿⣿⣿⣿⣿⣿⣿⣿⣿
⣿⣿⣿⣿⣿⣿⣿⣿⣿⣿⣿⣿⣧⣶⣷⣾⣷⠔⠀⢀⣴⣿⣧⡀⠀⠀⠀⠀⠀⠀⠙⠛⠿⢿⠃⣼⢳⠦⡉⠀⠀⠀⠀⠀⢠⣿⣿⣧⡀⢹⣿⣿⣿⣿⣿⣿⣿⣿⣿⣿⣿⣿⣿⣿⣿⣿

No sabes lo que estoy apunto de hacer
porque ni siquiera yo lo sé
```
