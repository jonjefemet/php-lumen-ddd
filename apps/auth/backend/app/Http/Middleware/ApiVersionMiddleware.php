<?php

declare(strict_types=1);

namespace Finger\Apps\Auth\Backend\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

final class ApiVersionMiddleware
{
    private const SUPPORTED_VERSIONS = ['v1'];
    private const DEFAULT_VERSION = 'v1';
    private const DEPRECATED_VERSIONS = [];

    public function handle(Request $request, Closure $next, string $requiredVersion = null)
    {
        $version = $this->extractVersion($request, $requiredVersion);
        
        // Validate version
        if (!in_array($version, self::SUPPORTED_VERSIONS)) {
            return $this->createErrorResponse(
                "API version '{$version}' is not supported. Supported versions: " . implode(', ', self::SUPPORTED_VERSIONS),
                400
            );
        }

        // Add version to request attributes for controllers
        $request->attributes->set('api_version', $version);

        // Check for deprecated versions
        if (in_array($version, self::DEPRECATED_VERSIONS)) {
            $response = $next($request);
            if ($response instanceof JsonResponse) {
                $response->header('X-API-Deprecated', 'true');
                $response->header('X-API-Deprecation-Info', 'This API version is deprecated. Please migrate to the latest version.');
            }
            return $response;
        }

        $response = $next($request);
        
        // Add version headers to response
        if ($response instanceof JsonResponse) {
            $response->header('X-API-Version', $version);
        }

        return $response;
    }

    private function extractVersion(Request $request, ?string $requiredVersion): string
    {
        // 1. Use required version if specified
        if ($requiredVersion) {
            return $requiredVersion;
        }

        // 2. Extract from URL path (/api/v1/...)
        $path = $request->path();
        if (preg_match('/^api\/(v\d+)\//', $path, $matches)) {
            return $matches[1];
        }

        // 3. Check Accept header (application/vnd.finger.v1+json)
        $acceptHeader = $request->header('Accept', '');
        if (preg_match('/application\/vnd\.finger\.(v\d+)\+json/', $acceptHeader, $matches)) {
            return $matches[1];
        }

        // 4. Check X-API-Version header
        $versionHeader = $request->header('X-API-Version');
        if ($versionHeader && in_array($versionHeader, self::SUPPORTED_VERSIONS)) {
            return $versionHeader;
        }

        // 5. Default version
        return self::DEFAULT_VERSION;
    }

    private function createErrorResponse(string $message, int $status): JsonResponse
    {
        return response()->json([
            'error' => $message,
            'supported_versions' => self::SUPPORTED_VERSIONS,
            'version_info' => [
                'url_format' => '/api/{version}/endpoint',
                'header_format' => 'X-API-Version: v1',
                'accept_format' => 'Accept: application/vnd.finger.v1+json'
            ]
        ], $status);
    }
}
