<?php


namespace MisterIcy\RnR\Controller;


use MisterIcy\RnR\Entity\Leave;
use MisterIcy\RnR\Entity\Status;
use MisterIcy\RnR\Exceptions\ForbiddenException;
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
        $approveLink = sprintf(
            "http://%s:%d/build/#!/approve/%d",
            $_SERVER['SERVER_NAME'],
            $_SERVER['SERVER_PORT'],
            $leave->getId()
        );
        $rejectLink = sprintf(
            "http://%s:%d/build/#!/reject/%d",
            $_SERVER['SERVER_ADDR'],
            $_SERVER['SERVER_PORT'],
            $leave->getId()
        );


        // Send email

        $message = <<<MSG
Dear supervisor, <br/>
 employee {$requester} requested for some time off, starting on {$vacationStart} and ending on {$vacationEnd}, stating the reason:<br />
{$leave->getReason()}<br/><br/>
Click on one of the below links to approve or reject the application:<br/>
<a href="{$approveLink}">Approve</a> - <a href="{$rejectLink}">Reject</a>
MSG;

        $mailer = new Mailer();
        $result = $mailer->createMessage(
            ['icyd3mon@gmail.com' => 'Alexandros Koutroulis'],
            'New Leave Request',
            $message
        );


        return new Response(Response::HTTP_CREATED, $leave);
    }

    /**
     * Approve a leave.
     *
     * @RestAnnotation(method="PATCH", uri="leave/{leaveId}", protected=true, admin=true)
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

        $leave->setApprover($this->getActor())
            ->setStatus($status)
            ->setModifiedDate(new \DateTime());

        /** Send Email */

        $this->getEntityManager()->persist($leave);
        $this->getEntityManager()->flush();

        return new Response(Response::HTTP_OK, $leave);
    }

    /**
     * Refuse a leave
     *
     * @RestAnnotation(method="DELETE", uri="leave/{leaveId}", protected=true, admin=true)
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

        $leave->setApprover($this->getActor())
            ->setStatus($status)
            ->setModifiedDate(new \DateTime());

        /** Send Email */

        $this->getEntityManager()->persist($leave);
        $this->getEntityManager()->flush();

        return new Response(Response::HTTP_OK, $leave);
    }

    /**
     * List leaves of a user.
     *
     * @RestAnnotation(method="GET", uri="leaves/{userId}", protected=false, admin=false, anonymous=true)
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

    private function getMailTransport(): \Swift_SmtpTransport
    {
        global $transport;

        return $transport;
    }
}
