<?php


namespace MisterIcy\RnR;

/**
 * @Annotation
 * @Target({"METHOD"})
 */

final class RestAnnotation
{
    /**
     * HTTP Request Method
     * @Enum({"GET", "POST", "PUT", "PATCH", "DELETE"})
     * @var string
     */
    public string $method;

    /**
     * @var string URI of the Request
     */
    public string $uri;

    /**
     * Anonymous Access?
     * @var bool
     */
    public bool $anonymous = false;

    /**
     * Admin Access?
     *
     * @var bool
     */
    public bool $admin = false;

    /**
     * Protected Access?
     *
     * @var bool
     */
    public bool $protected = true;
}
