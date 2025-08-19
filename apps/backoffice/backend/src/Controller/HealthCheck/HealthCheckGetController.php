<?php

declare(strict_types=1);

namespace Finger\Apps\Backoffice\Backend\Controller\HealthCheck;

use Finger\Shared\Infrastructure\Http\Route;
use Finger\Shared\Infrastructure\Symfony\ApiController;
use Symfony\Component\HttpFoundation\JsonResponse;

#[Route('/health', 'GET', 'backoffice_health_check')]
final class HealthCheckGetController extends ApiController
{
    public function __invoke(): JsonResponse
    {
        return new JsonResponse([
            'status' => 'ok',
            'service' => 'backoffice-backend',
            'timestamp' => date('c')
        ]);
    }
}
