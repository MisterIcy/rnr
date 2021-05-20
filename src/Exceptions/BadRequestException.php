<?php


namespace MisterIcy\RnR\Exceptions;


use Throwable;

final class BadRequestException extends HttpException
{
    public function __construct(
        string $message,
        int $code = 0,
        array $headers = [],
        ?array $data = null,
        ?Throwable $previous = null
    ) {
        parent::__construct(400, $message, $code, $headers, $data, $previous);
    }

}
