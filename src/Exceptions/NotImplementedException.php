<?php


namespace MisterIcy\RnR\Exceptions;


use MisterIcy\RnR\Response;

final class NotImplementedException extends HttpException
{
    public function __construct(
        string $message = "This feature is not yet implemented"
    ) {
        parent::__construct(Response::HTTP_NOT_IMPLEMENTED, $message);
    }

}
