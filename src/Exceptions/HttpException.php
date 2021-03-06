<?php
declare(strict_types=1);


namespace MisterIcy\RnR\Exceptions;


use Throwable;
use Exception;
abstract class HttpException extends Exception
{
    /**
     * HTTP Status Code
     * @var int
     */
    private int $statusCode;

    /**
     * HTTP Headers
     * @var array<string, string>
     */
    private array $headers;

    /**
     * Extra data
     * @var array<string, mixed>|null
     */
    private ?array $data;

    /**
     * HttpException constructor.
     * @param int $statusCode
     * @param string $message
     * @param int $code
     * @param array<string, string> $headers
     * @param array<string, mixed>|null $data
     * @param \Throwable|null $previous
     */
    public function __construct(
        int $statusCode,
        string $message,
        int $code = 0,
        array $headers = [],
        ?array $data = null,
        ?Throwable $previous = null
    ) {
        $this->statusCode = $statusCode;
        $this->headers = $headers;
        $this->data = $data;
        parent::__construct($message, $code, $previous);
    }

    /**
     * @return int
     */
    public function getStatusCode(): int
    {
        return $this->statusCode;
    }

    /**
     * @return array<string, string>
     */
    public function getHeaders(): array
    {
        return $this->headers;
    }

    /**
     * @return mixed[]|null
     */
    public function getData(): ?array
    {
        return $this->data;
    }


}
