<?php

declare(strict_types=1);
require_once '../vendor/autoload.php';

use MisterIcy\RnR\Application;
use MisterIcy\RnR\Response;

/**
 * Global Exception / Error Handler
 *
 * @param \Throwable $ex
 * @return void
 */
function globalExceptionHandler(Throwable $ex) : void
{
    $response = new Response(
        Response::HTTP_INTERNAL_SERVER_ERROR,
        [
            'code' => $ex->getCode(),
            'message' => sprintf(
                "%s: %s",
                (new ReflectionClass($ex))->getShortName(),
                $ex->getMessage()

            ),
            'data' => null,
        ]
    );
    header('content-type: application/json');
    http_response_code($response->getStatusCode());
    print(json_encode($response->getData()));
    exit(1);
}

set_exception_handler('globalExceptionHandler');
set_error_handler('globalExceptionHandler');
$dotenv = \Dotenv\Dotenv::createImmutable(__DIR__.'/..');
$dotenv->load();

$dotenv->required(['DATABASE_HOST', 'DATABASE_PORT', 'DATABASE_USER', 'DATABASE_PASS', 'DATABASE_SCHEMA']);


$application = new Application();
$response = $application->run();

header('content-type: application/json');
foreach ($response->getHeaders() as $headerName => $value) {
    header("$headerName: $value");
}

http_response_code($response->getStatusCode());
print(json_encode($response->getData()));
exit(0);
