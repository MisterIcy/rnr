<?php


namespace MisterIcy\RnR;


final class Response
{
    public const HTTP_OK = 200;
    public const HTTP_CREATED = 201;

    public const HTTP_BAD_REQUEST = 400;
    public const HTTP_UNAUTHORIZED = 401;
    public const HTTP_FORBIDDEN = 403;
    public const HTTP_NOT_FOUND = 404;
    public const HTTP_METHOD_NOT_ALLOWED = 405;
    public const HTTP_INTERNAL_SERVER_ERROR = 500;
    public const HTTP_NOT_IMPLEMENTED = 501;

    /**
     * @var int
     */
    private int $statusCode;
    /**
     * @var mixed|null
     */
    private $data = null;
    /**
     * @var array<string, string>
     */
    private array $headers = [];

    /**
     * Response constructor.
     * @param int $statusCode
     * @param mixed|null $data
     * @param array<string, string> $headers
     */
    public function __construct(
        int $statusCode = self::HTTP_OK,
        $data = null,
        array $headers = []
    ) {
        $this->statusCode = $statusCode;
        $this->data = $data;
        $this->headers = $headers;
    }

    /**
     * @return int
     */
    public function getStatusCode(): int
    {
        return $this->statusCode;
    }

    /**
     * @param int $statusCode
     * @return Response
     */
    public function setStatusCode(int $statusCode): Response
    {
        $this->statusCode = $statusCode;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * @param mixed $data
     * @return Response
     */
    public function setData($data): Response
    {
        $this->data = $data;

        return $this;
    }

    /**
     * @return array<string, string>
     */
    public function getHeaders(): array
    {
        return $this->headers;
    }

    /**
     * @param array<string, string> $headers
     * @return Response
     */
    public function setHeaders(array $headers): Response
    {
        $this->headers = $headers;

        return $this;
    }


}
