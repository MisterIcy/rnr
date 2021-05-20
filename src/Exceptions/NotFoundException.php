<?php declare(strict_types=1);


namespace MisterIcy\RnR\Exceptions;


use Throwable;

class NotFoundException extends HttpException
{
    public function __construct(
        string $message,
        int $code = 0,
        array $headers = [],
        ?array $data = null,
        ?Throwable $previous = null
    ) {
        parent::__construct(404, $message, $code, $headers, $data, $previous);
    }
}
