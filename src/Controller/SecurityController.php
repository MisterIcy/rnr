<?php


namespace MisterIcy\RnR\Controller;


use MisterIcy\RnR\Exceptions\MethodNotAllowedException;
use MisterIcy\RnR\Exceptions\UnauthorizedException;
use MisterIcy\RnR\JWT;
use MisterIcy\RnR\Response;

class SecurityController extends AbstractRestController
{

    public function handle(): Response
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            throw new MethodNotAllowedException(
                $_SERVER['REQUEST_METHOD'],
                ['POST']
            );
        }
        $data = $this->getData();

        $connection = new \PDO('mysql:host=192.168.1.66;dbname=rnr', 'icydemon', '!Soulsting1');
        $statement = $connection->prepare('SELECT id, password FROM users WHERE email = ? LIMIT 1');
        $statement->bindParam(1, $data['email']);
        $statement->execute();

        $result = $statement->fetch(\PDO::FETCH_ASSOC);

        if (!password_verify($data['password'], $result['password'])) {
            throw new UnauthorizedException("Invalid password");
        }

        return new Response(200, ['token' => JWT::createToken([$result['id']])]);
    }
}
