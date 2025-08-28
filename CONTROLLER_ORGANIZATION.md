# Organización de Controladores por Contexto

Esta documentación describe la nueva estructura organizacional de controladores en el módulo auth, siguiendo principios de separación por contexto y responsabilidad única.

## 📁 Estructura de Controladores

```
apps/auth/backend/app/Http/Controllers/
├── Auth/                           # Contexto de Autenticación
│   ├── LoginController.php        # Recurso: Autenticación de usuarios
│   ├── RegisterController.php     # Recurso: Registro de usuarios
│   └── RefreshTokenController.php # Recurso: Renovación de tokens
└── System/                        # Contexto de Sistema
    └── HealthController.php       # Recurso: Health checks
```

## 🎯 Principios de Organización

### 1. **Separación por Contexto**
- **Auth/**: Todo lo relacionado con autenticación y autorización
- **System/**: Funcionalidades del sistema (health, status, etc.)

### 2. **Un Controlador por Recurso**
- **LoginController**: Solo maneja el login
- **RegisterController**: Solo maneja el registro
- **RefreshTokenController**: Solo maneja refresh de tokens
- **HealthController**: Solo maneja health checks

### 3. **Responsabilidad Única**
- Cada controlador tiene una sola responsabilidad
- Métodos `__invoke()` para controladores de acción única
- Fácil testing y mantenimiento

## 🚀 Namespaces y Rutas

### Namespaces
```php
// Auth Controllers
Finger\Apps\Auth\Backend\Http\Controllers\Auth\LoginController
Finger\Apps\Auth\Backend\Http\Controllers\Auth\RegisterController
Finger\Apps\Auth\Backend\Http\Controllers\Auth\RefreshTokenController

// System Controllers
Finger\Apps\Auth\Backend\Http\Controllers\System\HealthController
```

### Configuración de Rutas
```php
// System routes
$router->group(['prefix' => '', 'namespace' => 'System'], function () use ($router) {
    $router->get('/health', 'HealthController');
});

// Auth routes
$router->group(['prefix' => 'api/auth', 'namespace' => 'Auth'], function () use ($router) {
    $router->post('/login', 'LoginController');
    $router->post('/register', 'RegisterController');
    $router->post('/refresh', 'RefreshTokenController');
});
```

## 📋 Endpoints Disponibles

| Método | Endpoint | Controlador | Descripción |
|--------|----------|-------------|-------------|
| GET | `/health` | System/HealthController | Health check del servicio |
| POST | `/api/auth/login` | Auth/LoginController | Autenticación de usuario |
| POST | `/api/auth/register` | Auth/RegisterController | Registro de usuario |
| POST | `/api/auth/refresh` | Auth/RefreshTokenController | Renovación de tokens |

## 🔧 Características de los Controladores

### Auth/LoginController
```php
- Valida email y password
- Usa AuthenticateUserCommand
- Retorna tokens JWT
- Manejo de errores de validación
```

### Auth/RegisterController
```php
- Valida email, password y name
- Genera UUID automáticamente
- Usa CreateUserCommand
- Retorna confirmación de registro
```

### Auth/RefreshTokenController
```php
- Valida refresh_token
- Usa JwtTokenManager directamente
- Retorna nuevos tokens
- Manejo de tokens expirados/inválidos
```

### System/HealthController
```php
- No requiere autenticación
- Retorna status del servicio
- Incluye timestamp
- Útil para monitoring
```

## ✅ Beneficios de esta Organización

1. **🧩 Modularidad**: Cada controlador es independiente
2. **🔍 Claridad**: Fácil ubicar funcionalidad específica
3. **🧪 Testing**: Tests más focalizados y simples
4. **📈 Escalabilidad**: Fácil agregar nuevos contextos/recursos
5. **🛠️ Mantenimiento**: Cambios localizados sin afectar otros recursos
6. **📖 Legibilidad**: Código más limpio y comprensible

## 🔮 Extensibilidad Futura

Para agregar nuevos recursos/contextos:

```php
// Nuevo contexto: Profile
Controllers/Profile/
├── GetProfileController.php
├── UpdateProfileController.php
└── DeleteProfileController.php

// Nuevo contexto: Password
Controllers/Password/
├── ForgotPasswordController.php
├── ResetPasswordController.php
└── ChangePasswordController.php
```

Esta estructura permite crecer de manera organizada y mantenible! 🚀
