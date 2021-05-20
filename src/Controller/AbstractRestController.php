<?php


namespace MisterIcy\RnR\Controller;


use MisterIcy\RnR\Exceptions\UnauthorizedException;
use MisterIcy\RnR\JWT;

abstract class AbstractRestController implements ControllerInterface
{
    public function getData(): ?array
    {
        return json_decode(file_get_contents('php://input'), true);
    }
    public function validateRequest() : bool {
        $headers = getallheaders();
        if (!array_key_exists('Authorization', $headers)) {
            throw new UnauthorizedException('Authorization required');
        }

        $jwt  = explode(" ",$headers['Authorization'])[1];
        return JWT::validateToken($jwt);
    }
    protected function getRouteParts() : array
    {
        return explode("/", $_SERVER['REQUEST_URI']);
    }

}
