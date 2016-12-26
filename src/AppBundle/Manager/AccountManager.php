<?php

namespace AppBundle\Manager;

use Doctrine\ORM\EntityManager;
use AppBundle\Entity\User;
use Psr\Log\LoggerInterface;
use AppBundle\Entity\Account;

class AccountManager
{
    private $em;
    private $logger;

    public function __construct(EntityManager $em, LoggerInterface $logger)
    {
        $this->em = $em;
        $this->logger = $logger;
    }

    public function getByNumber(string $number) {
        return $this->em->getRepository('AppBundle:Account')->findOneByNumber($number);
    }

    public function completeBalances($accounts) {

        $accountsMap = [];
        foreach ($accounts as $account) {
            if ($account instanceof Account) {
                $account->setBalance(0);
                $accountsMap[$account->getId()] = $account;
            }
        }

        if (count($accountsMap) > 0) {
            $result = $this->em->getRepository('AppBundle:Operation')->sumAmountByAccount(array_keys($accountsMap));
            foreach ($result as $row) {
                $accountsMap[$row['id']]->setBalance($row['balance']);
            }
        }
    }
}