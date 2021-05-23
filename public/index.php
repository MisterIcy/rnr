<?php declare(strict_types=1);
require_once __DIR__ . '/../vendor/autoload.php';


use JMS\Serializer\Naming\IdenticalPropertyNamingStrategy;
use JMS\Serializer\Naming\SerializedNameAnnotationStrategy;
use MisterIcy\RnR\Application;
use MisterIcy\RnR\Response;

/**
 * Global Exception / Error Handler
 *
 * @param \Throwable $ex
 * @return void
 */
function globalExceptionHandler(Throwable $ex): void
{
    $code = Response::HTTP_INTERNAL_SERVER_ERROR;
    if ($ex instanceof \MisterIcy\RnR\Exceptions\HttpException) {
        /** @var \MisterIcy\RnR\Exceptions\HttpException $exception */
        $exception = $ex;
        $code = $exception->getStatusCode();
    }

    $response = new Response(
        $code,
        [
            'code' => $code,
            'message' => sprintf(
                "%s: %s",
                (new ReflectionClass($ex))->getShortName(),
                $ex->getMessage()

            ),
            'data' => $ex->getTrace(),
        ]
    );
    http_response_code($response->getStatusCode());
    header('content-type: application/json');
    print(json_encode($response->getData()));
    exit(1);
}

set_exception_handler('globalExceptionHandler');
set_error_handler('globalExceptionHandler');

require_once __DIR__ . '/../bootstrap.php';

$application = new Application();
$response = $application->run();

header('content-type: application/json');
foreach ($response->getHeaders() as $headerName => $value) {
    header("$headerName: $value");
}

http_response_code($response->getStatusCode());
$serializer = JMS\Serializer\SerializerBuilder::create()
    ->setPropertyNamingStrategy(
        new SerializedNameAnnotationStrategy(new IdenticalPropertyNamingStrategy())
    )
    ->build();
$jsonContent = $serializer->serialize($response->getData(), 'json');

print($jsonContent);
exit(0);
