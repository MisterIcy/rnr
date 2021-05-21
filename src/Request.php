<?php
declare(strict_types=1);


namespace MisterIcy\RnR;

/**
 * Represents an HTTP Request
 * @package MisterIcy\RnR
 */
final class Request
{
    /** @var string */
    private string $method;

    /** @var string */
    private string $uri;

    /**
     * @var array<string, string>|null
     */
    private array $headers;

    public function __construct()
    {
        $this->method = $_SERVER['REQUEST_METHOD'];
        $this->uri = $_SERVER['REQUEST_URI'];
        $this->headers = getallheaders();
    }

    /**
     * @return string
     */
    public function getMethod(): string
    {
        return $this->method;
    }

    /**
     * @return string
     */
    public function getUri(): string
    {
        return $this->uri;
    }

    /**
     * @return array<string, string>|null
     */
    public function getHeaders(): ?array
    {
        return $this->headers;
    }

    public function getHeader(string $name): ?string
    {
        return $this->hasHeader($name) ? $this->headers[$name] : null;
    }

    public function hasHeader(string $name): bool
    {
        return array_key_exists($name, $this->headers);
    }

    public function getUriParts(): array
    {
        $parts = explode("/", $this->uri);
        array_shift($parts);

        return $parts;
    }

    /**
     * Returns a Request's Data
     * @return array|null
     */
    public function getData(): ?array
    {
        return json_decode(file_get_contents('php://input'), true);
    }
}
