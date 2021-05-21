<?php


namespace MisterIcy\RnR\Exceptions;


use MisterIcy\RnR\Response;
use Throwable;

final class InternalServerErrorException extends HttpException
{
    public function __construct(
        string $message
    ) {
        parent::__construct(Response::HTTP_INTERNAL_SERVER_ERROR, $message);
    }
}
