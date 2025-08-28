<?php

declare(strict_types=1);

namespace Finger\Apps\Auth\Backend\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Laravel\Lumen\Routing\Controller;

final class HealthController extends Controller
{
    public function check(): JsonResponse
    {
        return response()->json([
            'status' => 'ok',
            'service' => 'auth-backend',
            'timestamp' => date('c')
        ]);
    }
}
