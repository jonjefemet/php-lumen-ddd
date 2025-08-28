<?php

declare(strict_types=1);

namespace Finger\Apps\Auth\Backend\Http\Controllers\V1\System;

use Illuminate\Http\JsonResponse;
use Laravel\Lumen\Routing\Controller;
use OpenApi\Generator;

final class DocsController extends Controller
{
    public function json(): JsonResponse
    {
        try {
            // Scan controllers and global spec using zircote/swagger-php
            $scanPaths = [
                __DIR__ . '/../Auth',             // Auth controllers
                __DIR__ . '/../System',           // System controllers  
                __DIR__ . '/../OpenApiSpec.php'   // Global OpenAPI spec
            ];
            
            // Generate OpenAPI documentation from PHP Attributes
            $openapi = Generator::scan($scanPaths);
            
            // Convert to JSON and return
            $jsonString = $openapi->toJson();
            $jsonArray = json_decode($jsonString, true);
            
            return response()->json($jsonArray);
            
        } catch (\Throwable $e) {
            return response()->json([
                'error' => 'Failed to generate OpenAPI documentation',
                'message' => $e->getMessage(),
                'note' => 'Check that all controllers have proper OpenApi\Attributes'
            ], 500);
        }
    }
}
