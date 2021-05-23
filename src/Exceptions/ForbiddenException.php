<?php

namespace MisterIcy\RnR\Exceptions;

use MisterIcy\RnR\Response;
use Throwable;

final class ForbiddenException extends HttpException
{
    /**
     * ForbiddenException constructor.
     * @param string $message
     */
    public function __construct(
        string $message = 'Access to this resource is forbidden'
    ) {
        parent::__construct(Response::HTTP_FORBIDDEN, $message);
    }
}
