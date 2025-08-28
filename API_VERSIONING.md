# API Versioning Strategy

Esta documentación describe la estrategia de versionamiento implementada para la API de autenticación, siguiendo las mejores prácticas de la industria.

## 🎯 Estrategia de Versionamiento

### Tipo de Versionamiento: **URL Path Versioning**
- **Formato**: `/api/v{number}/endpoint`
- **Ejemplo**: `/api/v1/auth/login`
- **Razón**: Claro, explícito y fácil de usar

## 📁 Estructura de Controladores Versionados

```
apps/auth/backend/app/Http/Controllers/
└── V1/                             # Versión 1 de la API
    ├── Auth/                       # Contexto de Autenticación
    │   ├── LoginController.php     # POST /api/v1/auth/login
    │   ├── RegisterController.php  # POST /api/v1/auth/register
    │   └── RefreshTokenController.php # POST /api/v1/auth/refresh
    └── System/                     # Contexto de Sistema
        └── HealthController.php    # GET /api/v1/health
```

## 🚀 Endpoints Disponibles

### Versión 1 (v1) - Actual

| Método | Endpoint | Controlador | Descripción |
|--------|----------|-------------|-------------|
| GET | `/api/v1/health` | V1\System\HealthController | Health check versionado |
| POST | `/api/v1/auth/login` | V1\Auth\LoginController | Login versionado |
| POST | `/api/v1/auth/register` | V1\Auth\RegisterController | Registro versionado |
| POST | `/api/v1/auth/refresh` | V1\Auth\RefreshTokenController | Refresh versionado |

### Legacy Routes (Compatibilidad hacia atrás)

| Método | Endpoint | Redirect | Estado |
|--------|----------|----------|---------|
| GET | `/health` | → V1\System\HealthController | ✅ Activo |
| POST | `/api/auth/login` | → V1\Auth\LoginController | ✅ Activo |
| POST | `/api/auth/register` | → V1\Auth\RegisterController | ✅ Activo |
| POST | `/api/auth/refresh` | → V1\Auth\RefreshTokenController | ✅ Activo |

## 📋 Características de Respuesta

### Respuestas Versionadas
Todas las respuestas incluyen información de versión:

```json
{
  "message": "Authentication successful",
  "data": { /* ... */ },
  "version": "v1"
}
```

### Health Check Mejorado
```json
{
  "status": "ok",
  "service": "auth-backend",
  "version": "v1",
  "timestamp": "2025-08-28T17:23:26+00:00",
  "uptime": "03:34:35"
}
```

## 🛠️ Middleware de Versionamiento

### ApiVersionMiddleware
Middleware opcional que maneja:

- **Extracción de versión** de múltiples fuentes
- **Validación** de versiones soportadas
- **Headers** de versión en respuestas
- **Deprecation warnings** para versiones obsoletas

### Formas de Especificar Versión

1. **URL Path** (Recomendado):
   ```
   GET /api/v1/health
   ```

2. **Header X-API-Version**:
   ```
   X-API-Version: v1
   ```

3. **Accept Header**:
   ```
   Accept: application/vnd.finger.v1+json
   ```

## 🔄 Estrategia de Migración

### Proceso de Versionamiento

1. **Nueva Versión**:
   ```
   Controllers/V2/Auth/LoginController.php
   ```

2. **Rutas Versionadas**:
   ```php
   $router->group(['prefix' => 'api/v2'], function () use ($router) {
       // New V2 routes
   });
   ```

3. **Deprecación Gradual**:
   - V1 → Active
   - V2 → Released
   - V1 → Deprecated (with warnings)
   - V1 → Removed

### Compatibilidad hacia Atrás

- **Legacy routes** mantienen funcionalidad
- **Gradual deprecation** con warnings
- **Clear migration path** para clientes

## 📈 Beneficios del Versionamiento

### ✅ Para Desarrolladores
- **Evolución controlada** de la API
- **Breaking changes** sin afectar clientes
- **Testing independiente** por versión
- **Rollback** fácil si es necesario

### ✅ Para Clientes
- **Estabilidad** de endpoints existentes
- **Migración gradual** a nuevas versiones
- **Predictibilidad** en cambios
- **Clear deprecation timeline**

### ✅ Para Operaciones
- **Monitoring** por versión
- **Analytics** de uso por versión
- **Performance** tracking independiente
- **Security patches** localizados

## 🔮 Planificación de Versiones Futuras

### V2 Potencial (Futuro)
```
- Enhanced authentication (2FA)
- OAuth2 integration
- Improved error responses
- Rate limiting per user
```

### V3 Potencial (Futuro)
```
- GraphQL support
- WebSocket authentication
- Microservices integration
- Advanced security features
```

## 🎯 Mejores Prácticas Implementadas

1. **URL Path Versioning**: Claro y explícito
2. **Semantic Versioning**: v1, v2, v3...
3. **Backward Compatibility**: Legacy routes supported
4. **Response Versioning**: Version info in responses
5. **Documentation**: Per-version documentation
6. **Testing**: Version-specific test suites
7. **Monitoring**: Version-aware metrics

¡El versionamiento está implementado siguiendo las mejores prácticas de la industria! 🚀
