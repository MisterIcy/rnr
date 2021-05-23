<?php


namespace MisterIcy\RnR\Exceptions;


use MisterIcy\RnR\Response;

final class MethodNotAllowedException extends HttpException
{
    /**
     * MethodNotAllowedException constructor.
     * @param string $given
     * @param array<string>|null $allowed
     */
    public function __construct(
        string $given,
        ?array $allowed
    ) {
        parent::__construct(
            Response::HTTP_METHOD_NOT_ALLOWED,
            sprintf(
                "%s is not allowed for this endpoint. Allowed: %s",
                $given,
                implode(',', $allowed)
            )
        );
    }
}
