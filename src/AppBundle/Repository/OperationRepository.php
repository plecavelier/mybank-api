<?php

namespace AppBundle\Repository;

use AppBundle\Entity\User;
use AppBundle\Entity\Operation;
use AppBundle\Entity\Account;
use AppBundle\Entity\OperationYearMonth;

/**
 * OperationRepository
 */
class OperationRepository extends \Doctrine\ORM\EntityRepository
{

    public function findAllOperationYearMonths(User $user) {
        // TODO : refactoring
        $sql = '
            SELECT DISTINCT YEAR(o.date) AS year, MONTH(o.date) AS month
            FROM operation o
            LEFT JOIN account a ON o.account_id = a.id
            WHERE a.user_id = '.$user->getId().'
            ORDER BY o.date ASC';
        $query = $this->_em->getConnection()->query($sql);

        $result = [];
        while ($row = $query->fetch()) {
            $item = new OperationYearMonth();
            $item->setYear($row['year']);
            $item->setMonth($row['month']);
            $result[] = $item;
        }
        return $result;
    }

    public function sumAmountByAccount(User $user, Account $account = null) {
        // TODO : refactoring
        $queryBuilder = $this->_em->createQueryBuilder();
        $queryBuilder->select('a.id, SUM(o.amount) AS balance')
           ->from('AppBundle:Operation', 'o')
           ->leftJoin('o.account', 'a')
           ->where('a.user = :user')
           ->groupBy('o.account')
           ->setParameter('user', $user);
        if ($account != null) {
            $queryBuilder->addWhere('a = :account')
                ->setParameter('account', $account);
        }
        return $queryBuilder->getQuery()->getArrayResult();
    }
}
