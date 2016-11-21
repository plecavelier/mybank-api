<?php

namespace AppBundle\Manager;

use Doctrine\ORM\EntityManager;
use AppBundle\Entity\User;
use Psr\Log\LoggerInterface;

class OperationManager
{
    private $em;
    private $logger;

    public function __construct(EntityManager $em, LoggerInterface $logger)
    {
        $this->em = $em;
        $this->logger = $logger;
    }

    public function getAllOperationYearMonths(User $user) {
        return $this->em->getRepository('AppBundle:Operation')->findAllOperationYearMonths($user);
    }
}