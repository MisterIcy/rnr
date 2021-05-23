<?php declare(strict_types=1);


namespace MisterIcy\RnR\Exceptions;


use MisterIcy\RnR\Response;
use Throwable;

final class NotFoundException extends HttpException
{
    /**
     * NotFoundException constructor.
     * @param string $message
     * @param int $code
     * @param array<string, string> $headers
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
        parent::__construct(Response::HTTP_NOT_FOUND, $message, $code, $headers, $data, $previous);
    }
}
