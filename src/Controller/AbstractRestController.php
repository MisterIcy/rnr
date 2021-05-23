<?php

declare(strict_types=1);

namespace MisterIcy\RnR\Controller;

use Doctrine\ORM\EntityManager;
use MisterIcy\RnR\Entity\User;
use MisterIcy\RnR\Persistence;
use MisterIcy\RnR\Request;
use MisterIcy\RnR\Security;

/**
 * Class AbstractRestController
 * @package MisterIcy\RnR\Controller
 */
abstract class AbstractRestController
{
    /** @var \Doctrine\ORM\EntityManager|null */
    private ?EntityManager $entityManager;
    /**
     * @var \MisterIcy\RnR\Persistence
     */
    private Persistence $persistence;
    /**
     * @var \MisterIcy\RnR\Request
     */
    private Request $request;
    /**
     * @var \MisterIcy\RnR\Security
     */
    private Security $security;

    public function __construct()
    {
        global $entityManager;
        $this->entityManager = $entityManager;
        $this->request = new Request();
        $this->security = new Security();
        $this->persistence = new Persistence($this->entityManager);
    }

    /**
     * @return \MisterIcy\RnR\Persistence
     */
    public function getPersistence(): Persistence
    {
        return $this->persistence;
    }

    /**
     * @return \Doctrine\ORM\EntityManager
     */
    public function getEntityManager(): ?EntityManager
    {
        return $this->entityManager;
    }

    /**
     * @return \MisterIcy\RnR\Request
     */
    public function getRequest(): Request
    {
        return $this->request;
    }

    /**
     * Gets JSON data from HTTP Requests
     * @return array|null
     */
    protected function getData(): ?array
    {
        return json_decode(file_get_contents('php://input'), true);
    }

    /**
     * Gets the {@see User} who requested a resource.
     *
     * @return \MisterIcy\RnR\Entity\User
     * @throws \MisterIcy\RnR\Exceptions\InternalServerErrorException Thrown in the insane scenario where a user id contained in a valid token, does not exist in database
     * @throws \MisterIcy\RnR\Exceptions\UnauthorizedException Thrown if the requester hasn't supplied an Authorization header.
     */
    protected function getActor(): User
    {
        return $this->getSecurity()->getActor();
    }

    /**
     * @return \MisterIcy\RnR\Security
     */
    public function getSecurity(): Security
    {
        return $this->security;
    }
}
