# JWT Implementation Guide

Esta implementación simple de JWT para autenticación incluye generación de tokens y refresh tokens usando la librería `firebase/php-jwt`.

## Características Implementadas

- ✅ Generación de Access Tokens (JWT)
- ✅ Generación de Refresh Tokens (JWT)  
- ✅ Validación y verificación de tokens
- ✅ Refresh automático de tokens
- ✅ Middleware de autenticación (opcional)

## Configuración

### Variables de Entorno

Asegúrate de configurar la variable `JWT_SECRET` en tu archivo `.env`:

```env
JWT_SECRET=tu_clave_secreta_super_segura_aqui
```

⚠️ **Importante**: Usa una clave secreta fuerte en producción (mínimo 256 bits).

## Endpoints Disponibles

### 1. Login - Generar Tokens
```
POST /api/auth/login
Content-Type: application/json

{
    "email": "usuario@ejemplo.com",
    "password": "tu_password"
}
```

**Respuesta exitosa:**
```json
{
    "message": "Authentication successful",
    "data": {
        "access_token": "eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9...",
        "refresh_token": "eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9...",
        "token_type": "Bearer",
        "expires_in": 3600
    }
}
```

### 2. Refresh Token - Renovar Tokens
```
POST /api/auth/refresh
Content-Type: application/json

{
    "refresh_token": "eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9..."
}
```

**Respuesta exitosa:**
```json
{
    "message": "Token refresh successful",
    "data": {
        "access_token": "eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9...",
        "refresh_token": "eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9...",
        "token_type": "Bearer",
        "expires_in": 3600
    }
}
```

## Uso de Tokens

### En Requests Autenticados

Incluye el Access Token en el header `Authorization`:

```
Authorization: Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9...
```

### Configuración de Tiempos

- **Access Token**: 1 hora (3600 segundos)
- **Refresh Token**: 7 días (604800 segundos)

Puedes modificar estos valores en `AuthServiceProvider.php`:

```php
$container->singleton(JwtTokenService::class, function (Container $container) {
    return new JwtTokenService(
        $_ENV['JWT_SECRET'] ?? 'your-secret-key',
        'HS256',
        3600,  // Access token TTL (1 hora)
        604800 // Refresh token TTL (7 días)
    );
});
```

## Middleware de Autenticación (Opcional)

Para proteger rutas, puedes usar el middleware `JwtAuthMiddleware`:

```php
use Finger\Auth\Shared\Infrastructure\Middleware\JwtAuthMiddleware;

// En tu controlador o sistema de rutas
$middleware = new JwtAuthMiddleware($tokenManager);
$response = $middleware->handle($request);

if ($response !== null) {
    // Token inválido - retornar error
    return $response;
}

// Token válido - continuar con la lógica
$user = $request->attributes->get('authenticated_user');
```

## Estructura de Archivos Creados

```
src/Auth/Shared/Infrastructure/Jwt/
├── JwtTokenService.php      # Servicio básico de JWT
├── JwtTokenManager.php      # Manager de operaciones JWT
└── Middleware/
    └── JwtAuthMiddleware.php # Middleware de autenticación

apps/auth/backend/src/Controller/Auth/
└── RefreshTokenPostController.php # Controlador para refresh
```

## Flujo de Autenticación

1. **Login**: Cliente envía credenciales → Recibe access_token y refresh_token
2. **Requests Protegidos**: Cliente usa access_token en header Authorization
3. **Token Expirado**: Cliente usa refresh_token para obtener nuevos tokens
4. **Refresh Expirado**: Cliente debe hacer login nuevamente

## Seguridad

- Los tokens son firmados con HMAC-SHA256
- Incluyen timestamps de emisión y expiración
- Contienen identificador de usuario y tipo de token
- Los refresh tokens solo sirven para renovar tokens

¡La implementación está lista para usar! 🚀
