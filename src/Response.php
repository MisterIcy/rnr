<?php


namespace MisterIcy\RnR;


final class Response
{
    public const HTTP_OK = 200;
    public const HTTP_CREATED = 201;

    public const HTTP_UNAUTHORIZED = 401;
    public const HTTP_FORBIDDEN = 403;
    public const HTTP_METHOD_NOT_ALLOWED = 405;
    public const HTTP_INTERNAL_SERVER_ERROR = 500;

    private int $statusCode;
    private $data = null;
    private array $headers = [];

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
     * @return array
     */
    public function getHeaders(): array
    {
        return $this->headers;
    }

    /**
     * @param array $headers
     * @return Response
     */
    public function setHeaders(array $headers): Response
    {
        $this->headers = $headers;

        return $this;
    }


}
