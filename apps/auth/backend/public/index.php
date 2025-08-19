<?php

declare(strict_types=1);

use Finger\Apps\Auth\Backend\AuthBackendKernel;

require_once dirname(__DIR__, 4) . '/apps/bootstrap.php';

$kernel = new AuthBackendKernel($_SERVER['APP_ENV'] ?? 'dev', (bool) ($_SERVER['APP_DEBUG'] ?? true));
$kernel->run();
