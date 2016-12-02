<?php

namespace AppBundle\Manager;

use Doctrine\ORM\EntityManager;
use AppBundle\Entity\User;
use Psr\Log\LoggerInterface;
use AppBundle\Entity\Account;

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

    public function getAccountBalances(User $user, Account $account = null) {
        // TODO : refactoring
        $result = $this->em->getRepository('AppBundle:Operation')->sumAmountByAccount($user);
        $map = array();
        foreach ($result as $item) {
            $map[$item['id']] = $item['balance'];
        }
        return $map;
    }
}