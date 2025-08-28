<?php

declare(strict_types=1);

namespace Finger\Apps\Auth\Backend\Http\Controllers\V1;

use OpenApi\Attributes as OA;

#[OA\Info(
    title: "Finger Auth API",
    version: "1.0.0",
    description: "API de autenticación para el sistema Finger. Incluye registro, login, refresh de tokens JWT y health check.",
    contact: new OA\Contact(
        name: "Finger Development Team",
        email: "dev@finger.com"
    )
)]
#[OA\Server(
    url: "http://localhost:8001/api/v1",
    description: "Development Server V1"
)]
#[OA\Server(
    url: "http://localhost:8001",
    description: "Legacy endpoints (for backward compatibility)"
)]
#[OA\SecurityScheme(
    securityScheme: "BearerAuth",
    type: "http",
    scheme: "bearer",
    bearerFormat: "JWT",
    description: "JWT Authorization header using the Bearer scheme. Example: 'Authorization: Bearer {token}'"
)]
#[OA\Tag(
    name: "Authentication",
    description: "Endpoints related to user authentication and authorization"
)]
#[OA\Tag(
    name: "System",
    description: "System endpoints for health checks and monitoring"
)]
class OpenApiSpec
{
    // This class only serves as a container for OpenAPI attributes
}
