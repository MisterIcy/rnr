<?php


namespace MisterIcy\RnR\Controller;


use DateTime;
use MisterIcy\RnR\Entity\Leave;
use MisterIcy\RnR\Entity\Status;
use MisterIcy\RnR\Exceptions\ForbiddenException;
use MisterIcy\RnR\Exceptions\InternalServerErrorException;
use MisterIcy\RnR\Exceptions\NotFoundException;
use MisterIcy\RnR\Mailer;
use MisterIcy\RnR\Response;
use MisterIcy\RnR\RestAnnotation;

final class LeaveController extends AbstractRestController
{

    /**
     * Request a new leave
     *
     * @RestAnnotation(method="POST", uri="leave", protected=true, admin=false)
     * @throws \MisterIcy\RnR\Exceptions\InternalServerErrorException|\MisterIcy\RnR\Exceptions\UnauthorizedException
     */
    public function submitLeave(): Response
    {
        $data = $this->getData();
        $data['requester'] = ['id' => $this->getActor()->getId()];
        $data['status'] = ['id' => 1];
        /** @var Leave $leave */
        $leave = $this->getPersistence()
            ->persist((new Leave()), $data);

        $requester = strval($leave->getRequester());
        $vacationStart = $leave->getStartDate()->format('d/m/Y');
        $vacationEnd = $leave->getEndDate()->format('d/m/Y');

        $approveLink = $this->generateLink('approve', $leave->getId());
        $rejectLink = $this->generateLink('reject', $leave->getId());


        // Send email

        $message = <<<MSG
Dear supervisor, <br/>
 employee $requester requested for some time off, starting on $vacationStart and ending on $vacationEnd, stating the reason:<br />
{$leave->getReason()}<br/><br/>
Click on one of the below links to approve or reject the application:<br/>
<a href="$approveLink">Approve</a> - <a href="$rejectLink">Reject</a>
MSG;

        $mailer = new Mailer();
        $mailer->createMessage(
            [$_ENV['MAIL_FROM'] => $_ENV['MAIL_FROM_NAME']],
            'New Leave Request',
            $message
        );

        return new Response(Response::HTTP_CREATED, $leave);
    }

    /**
     * Helper to generate approve/reject link
     * @param string $action
     * @param int $id
     * @return string
     */
    private function generateLink(string $action, int $id): string
    {
        return sprintf(
            "http://%s:%d/build/#!/%s/%d",
            $_ENV['WEBSERVER_NAME'],
            $_ENV['WEBSERVER_PORT'],
            $action,
            $id
        );
    }

    /**
     * Approve a leave.
     *
     * @RestAnnotation(method="PATCH", uri="leave/{leaveId}", protected=true, admin=true)
     *
     * @param int $leaveId
     * @return Response
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     * @throws \MisterIcy\RnR\Exceptions\InternalServerErrorException
     * @throws \MisterIcy\RnR\Exceptions\NotFoundException
     * @throws \MisterIcy\RnR\Exceptions\UnauthorizedException
     */
    public function approveLeave($leaveId): Response
    {
        /** @var Status $status */
        $status = $this->getEntityManager()
            ->getRepository(Status::class)
            ->find(2);

        /** @var Leave|null $leave */
        $leave = $this->getEntityManager()
            ->getRepository(Leave::class)
            ->find($leaveId);

        if (is_null($leave)) {
            throw new NotFoundException("There is no leave with id $leaveId");
        }

        if (!is_null($leave->getApprover())) {
            throw new InternalServerErrorException(
                "The status of this leave is {$leave->getStatus()->getName()}. Contact the administrator"
            );
        }


        $leave->setApprover($this->getActor())
            ->setStatus($status)
            ->setModifiedDate(new DateTime());


        $this->getEntityManager()->persist($leave);
        $this->getEntityManager()->flush();

        $this->notifyUser($leave);

        return new Response(Response::HTTP_OK, $leave);
    }

    /**
     * Sends a notification email to the user
     * @param \MisterIcy\RnR\Entity\Leave $leave
     * @return int
     */
    private function notifyUser(Leave $leave): int
    {
        $message = <<<MSG
Dear employee, your supervisor has {$leave->getStatus()->getName()} your application
submitted on {$leave->getCreatedDate()->format('d/m/Y')}.
MSG;
        $mailer = new Mailer();

        return $mailer->createMessage(
            [$leave->getRequester()->getEmail() => strval($leave->getRequester())],
            "Leave Request {$leave->getStatus()->getName()}",
            $message
        );
    }

    /**
     * Reject a leave
     *
     * @RestAnnotation(method="DELETE", uri="leave/{leaveId}", protected=true, admin=true)
     *
     * @param int $leaveId
     * @return Response
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     * @throws \MisterIcy\RnR\Exceptions\InternalServerErrorException
     * @throws \MisterIcy\RnR\Exceptions\NotFoundException
     * @throws \MisterIcy\RnR\Exceptions\UnauthorizedException
     */
    public function refuseLeave($leaveId): Response
    {
        /** @var Status $status */
        $status = $this->getEntityManager()
            ->getRepository(Status::class)
            ->find(3);

        /** @var Leave|null $leave */
        $leave = $this->getEntityManager()
            ->getRepository(Leave::class)
            ->find($leaveId);

        if (is_null($leave)) {
            throw new NotFoundException("There is no leave with id $leaveId");
        }

        if (!is_null($leave->getApprover())) {
            throw new InternalServerErrorException(
                "The status of this leave is {$leave->getStatus()->getName()}. Contact the administrator"
            );
        }

        $leave->setApprover($this->getActor())
            ->setStatus($status)
            ->setModifiedDate(new DateTime());

        $this->getEntityManager()->persist($leave);
        $this->getEntityManager()->flush();

        $this->notifyUser($leave);

        return new Response(Response::HTTP_OK, $leave);
    }

    /**
     * Lists leaves of a user.
     *
     * @RestAnnotation(method="GET", uri="leaves/{userId}", protected=false, admin=false, anonymous=true)
     *
     * @param int $userId
     * @return Response
     * @throws \MisterIcy\RnR\Exceptions\ForbiddenException
     * @throws \MisterIcy\RnR\Exceptions\InternalServerErrorException
     * @throws \MisterIcy\RnR\Exceptions\NotFoundException
     * @throws \MisterIcy\RnR\Exceptions\UnauthorizedException
     */
    public function listLeaves($userId): Response
    {
        // If you are not an administrator...
        if (!$this->getSecurity()->isAdmin()) {
            // you have to be the one you are looking for
            if ($this->getActor()->getId() !== intval($userId)) {
                throw new ForbiddenException(
                    sprintf("You are %d and you requested %d", $this->getActor()->getId(), intval($userId))
                );
            }
        }

        $queryBuilder = $this->getEntityManager()->createQueryBuilder();

        $leaves = $queryBuilder->select('l')
            ->from(Leave::class, 'l')
            ->innerJoin(
                'l.requester',
                'u1',
                'WITH',
                $queryBuilder->expr()->eq('u1.id', ':userId')
            )
            ->orderBy($queryBuilder->expr()->desc('l.createdDate'))
            ->setParameter('userId', $userId)
            ->getQuery()
            ->getResult();

        if (empty($leaves)) {
            throw new NotFoundException("No leaves were found for this employee");
        }

        return new Response(Response::HTTP_OK, $leaves);
    }
}
