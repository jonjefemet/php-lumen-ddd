<?php

declare(strict_types=1);

use Finger\Apps\Backoffice\Backend\BackofficeBackendKernel;

require_once dirname(__DIR__, 4) . '/apps/bootstrap.php';

$kernel = new BackofficeBackendKernel($_SERVER['APP_ENV'] ?? 'dev', (bool) ($_SERVER['APP_DEBUG'] ?? true));
$kernel->run();
