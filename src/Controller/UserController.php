<?php

declare(strict_types=1);


namespace MisterIcy\RnR\Controller;

use MisterIcy\RnR\Entity\User;
use MisterIcy\RnR\Exceptions\ForbiddenException;
use MisterIcy\RnR\Exceptions\InternalServerErrorException;
use MisterIcy\RnR\Exceptions\NotFoundException;
use MisterIcy\RnR\Persistence;
use MisterIcy\RnR\Response;
use MisterIcy\RnR\RestAnnotation;

/**
 * User Controller.
 *
 * @package MisterIcy\RnR\Controller
 */
final class UserController extends AbstractRestController
{

    /**
     * Gets a user by their id.
     *
     * @param int $userId The id of the User we want to fetch from the database
     * @return \MisterIcy\RnR\Response
     * @throws \MisterIcy\RnR\Exceptions\ForbiddenException Thrown when a user tries to get data of another user
     * @throws \MisterIcy\RnR\Exceptions\InternalServerErrorException
     * @throws \MisterIcy\RnR\Exceptions\NotFoundException Thrown when the user was not found.
     * @throws \MisterIcy\RnR\Exceptions\UnauthorizedException
     *
     * @RestAnnotation(method="GET", uri="user/{userId}", protected=true)
     * @api
     */
    public function getUser($userId): Response
    {
        $user = $this->getEntityManager()
            ->getRepository(User::class)
            ->find($userId);

        if (empty($user)) {
            throw new NotFoundException("There is no such user {$userId}");
        }

        if ($user !== $this->getActor() && !$this->getSecurity()->isAdmin()) {
            throw new ForbiddenException();
        }

        return new Response(Response::HTTP_OK, $user);
    }

    /**
     * Lists all Users
     *
     * @RestAnnotation(method="GET", uri="users", protected=true, admin=true)
     *
     * @return \MisterIcy\RnR\Response
     * @throws \MisterIcy\RnR\Exceptions\InternalServerErrorException
     * @api
     */
    public function listUsers(): Response
    {
        $users = $this->getEntityManager()
            ->getRepository(User::class)
            ->findAll();

        if (empty($users)) {
            throw new InternalServerErrorException("Your database is not populated!");
        }

        return new Response(Response::HTTP_OK, $users);
    }

    /**
     * Creates a new User
     *
     * @return \MisterIcy\RnR\Response
     * @RestAnnotation(method="POST", uri="user", protected=true, admin=true)
     * @throws \MisterIcy\RnR\Exceptions\InternalServerErrorException
     * @api
     */
    public function createUser(): Response
    {
        $data = $this->getData();

        $data['password'] = password_hash($data['password'], PASSWORD_ARGON2ID);

        $user = $this->getPersistence()->persist(
            (new User()),
            $data
        );

        return new Response(Response::HTTP_CREATED, $user);
    }

    /**
     * Updates an existing user
     *
     * @param int $userId
     * @return \MisterIcy\RnR\Response
     * @throws \MisterIcy\RnR\Exceptions\ForbiddenException
     * @throws \MisterIcy\RnR\Exceptions\InternalServerErrorException
     * @throws \MisterIcy\RnR\Exceptions\NotFoundException
     * @throws \MisterIcy\RnR\Exceptions\UnauthorizedException
     * @RestAnnotation(method="PUT", uri="user/{userId}", protected=true)
     * @api
     */
    public function updateUser($userId): Response
    {
        $user = $this->getEntityManager()
            ->getRepository(User::class)
            ->find($userId);

        if (empty($user)) {
            throw new NotFoundException("There is no such user {$userId}");
        }

        if ($user !== $this->getActor() && !$this->getSecurity()->isAdmin()) {
            throw new ForbiddenException();
        }

        $data = $this->getData();
        if (isset($data['password'])) {
            $data['password'] = password_hash($data['password'], PASSWORD_ARGON2ID);
        }

        $user = $this->getPersistence()
            ->persist($user, $data);

        return new Response(Response::HTTP_OK, $user);
    }
}
