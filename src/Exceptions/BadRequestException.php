<?php


namespace MisterIcy\RnR\Exceptions;


use MisterIcy\RnR\Response;
use Throwable;

final class BadRequestException extends HttpException
{
    /**
     * BadRequestException constructor.
     * @param string $message
     * @param int $code
     * @param array<string,string> $headers
     * @param array<string, mixed>|null $data
     * @param \Throwable|null $previous
     */
    public function __construct(
        string $message,
        int $code = 0,
        array $headers = [],
        ?array $data = null,
        ?Throwable $previous = null
    ) {
        parent::__construct(Response::HTTP_BAD_REQUEST, $message, $code, $headers, $data, $previous);
    }

}
