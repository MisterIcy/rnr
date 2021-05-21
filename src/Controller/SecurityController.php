<?php declare(strict_types=1);

namespace MisterIcy\RnR\Controller;

use MisterIcy\RnR\Entity\User;
use MisterIcy\RnR\Exceptions\MethodNotAllowedException;
use MisterIcy\RnR\Exceptions\UnauthorizedException;
use MisterIcy\RnR\JWT;
use MisterIcy\RnR\Response;

/**
 * Handles the logging in portion of the Project
 *
 * @package MisterIcy\RnR\Controller
 */
final class SecurityController extends AbstractRestController
{

    /**
     * Handles a Request.
     *
     * This simply validates the User's credentials and returns a JSON Web Token
     * which is valid for 10 minutes.
     *
     * @return \MisterIcy\RnR\Response
     * @throws \MisterIcy\RnR\Exceptions\MethodNotAllowedException
     * @throws \MisterIcy\RnR\Exceptions\UnauthorizedException
     */
    public function handle(): Response
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            throw new MethodNotAllowedException(
                $_SERVER['REQUEST_METHOD'],
                ['POST']
            );
        }

        $data = $this->getData();
        /** @var User|null $user */
        $user = $this->getEntityManager()
            ->getRepository(User::class)
            ->findOneBy(
                ['email' => $data['email']]
            );
        if (is_null($user)) {
            throw new UnauthorizedException("User not found");
        }

        if (!password_verify($data['password'], $user->getPassword())) {
            throw new UnauthorizedException("Invalid password");
        }

        return new Response(200, ['token' => JWT::createToken(
            ['userId' => $user->getId(), 'isAdmin' => $user->getUserType()->isAdmin()]
        )]);
    }
}
