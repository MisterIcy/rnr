<?php


namespace MisterIcy\RnR;


use MisterIcy\RnR\Entity\User;
use MisterIcy\RnR\Exceptions\InternalServerErrorException;
use MisterIcy\RnR\Exceptions\UnauthorizedException;

final class Security
{
    public function validateLogin(): bool
    {
        return JWT::validateToken($this->getToken());
    }

    /**
     * Gets the JWT from the request
     *
     * @return string
     * @throws \MisterIcy\RnR\Exceptions\UnauthorizedException Thrown if the requester hasn't supplied an Authorization header.
     */
    private function getToken(): string
    {
        $headers = getallheaders();
        if (!array_key_exists('Authorization', $headers)) {
            throw new UnauthorizedException('No authorization header was found on your request');
        }

        $token = explode(":", $headers['Authorization'])[1];

        return ltrim($token, ' ');
    }
    /**
     * Checks if the requester is an admin.
     *
     * @return bool
     * @throws \MisterIcy\RnR\Exceptions\InternalServerErrorException
     * @throws \MisterIcy\RnR\Exceptions\UnauthorizedException
     */
    public function isAdmin(): bool
    {
        return $this->getActor()->getUserType()->isAdmin();
    }

    /**
     * Gets the {@see User} who requested a resource.
     *
     * @return \MisterIcy\RnR\Entity\User
     * @throws \MisterIcy\RnR\Exceptions\InternalServerErrorException Thrown in the insane scenario where a user id contained in a valid token, does not exist in database
     * @throws \MisterIcy\RnR\Exceptions\UnauthorizedException Thrown if the requester hasn't supplied an Authorization header.
     */
    public function getActor(): User
    {
        global $entityManager;
        $userId = $this->getTokenData($this->getToken())['userId'];
        /** @var User|null $user */
        $user = $entityManager
            ->getRepository(User::class)
            ->find($userId);

        if (is_null($user)) {
            throw new InternalServerErrorException("Something extremely bad happened.");
        }

        return $user;
    }

    /**
     * Gets the data part of the payload from the JWT.
     *
     * @param string $token
     * @return array<mixed>
     * @throws \MisterIcy\RnR\Exceptions\UnauthorizedException
     */
    private function getTokenData(string $token): array
    {
        JWT::validateToken($token, $payload);

        return $payload['data'];
    }
}
