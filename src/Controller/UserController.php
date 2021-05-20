<?php


namespace MisterIcy\RnR\Controller;


use Doctrine\DBAL\ForwardCompatibility\Result;
use MisterIcy\RnR\Exceptions\MethodNotAllowedException;
use MisterIcy\RnR\Exceptions\NotFoundException;
use MisterIcy\RnR\Exceptions\UnauthorizedException;
use MisterIcy\RnR\Response;

class UserController extends AbstractRestController
{
    private const ALLOWED_METHODS = ['GET', 'POST', 'PUT', 'PATCH'];
    public function handle(): Response
    {
        if (!in_array($_SERVER['REQUEST_METHOD'], self::ALLOWED_METHODS)) {
            throw new MethodNotAllowedException($_SERVER['REQUEST_METHOD'], self::ALLOWED_METHODS);
        }

        switch ($_SERVER['REQUEST_METHOD']) {
            case 'GET':
                return $this->getUser();
            case 'POST':
                return $this->createUser();
            case 'PUT':
            case 'PATCH':
                return $this->updateUser();
        }

    }
    public function createUser() : Response
    {

    }
    public function getUser() : Response
    {
        if (!$this->validateRequest()) {
            throw new UnauthorizedException("Authorization Required");
        }

        $parts = $this->getRouteParts();
        if (count($parts) < 3) {
            throw new NotFoundException("Route not found");
        }
        $userId = $parts[2];

        $connection = new \PDO('mysql:host=192.168.1.66;dbname=rnr', 'icydemon', '!Soulsting1');
        $statement = $connection->prepare('SELECT id, given_name, family_name, email FROM users WHERE id = ? LIMIT 1');
        $statement->bindParam(1, $userId);
        $statement->execute();

        $result = $statement->fetch(\PDO::FETCH_ASSOC);

        if (empty($result)) {
            throw new NotFoundException("There is no such user");
        }
        return new Response(200, $result);

    }
    public function updateUser() : Response
    {

    }

}
