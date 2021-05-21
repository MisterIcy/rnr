<?php


namespace MisterIcy\RnR\Exceptions;


use MisterIcy\RnR\Response;

final class UnauthorizedException extends HttpException
{
    public function __construct(
        string $message
    ) {
        parent::__construct(Response::HTTP_UNAUTHORIZED, $message);
    }

}
