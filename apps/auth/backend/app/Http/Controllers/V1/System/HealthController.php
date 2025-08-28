<?php

declare(strict_types=1);

namespace Finger\Apps\Auth\Backend\Http\Controllers\V1\System;

use Illuminate\Http\JsonResponse;
use Laravel\Lumen\Routing\Controller;

final class HealthController extends Controller
{
    public function __invoke(): JsonResponse
    {
        return response()->json([
            'status' => 'ok',
            'service' => 'auth-backend',
            'version' => 'v1',
            'timestamp' => date('c'),
            'uptime' => $this->getUptime()
        ]);
    }

    private function getUptime(): string
    {
        $uptime = file_get_contents('/proc/uptime');
        if ($uptime !== false) {
            $seconds = (float) explode(' ', $uptime)[0];
            return gmdate('H:i:s', (int) $seconds);
        }
        
        return 'unknown';
    }
}
