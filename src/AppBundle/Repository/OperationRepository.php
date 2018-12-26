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

    public function findAllYearMonthTuples(User $user) {
        $queryBuilder = $this->_em->createQueryBuilder();
        $queryBuilder->select('DISTINCT YEAR(o.date) AS year, MONTH(o.date) AS month')
            ->from('AppBundle:Operation', 'o')
            ->leftJoin('o.account', 'a')
            ->where('a.user = :user')
            ->setParameter('user', $user)
            ->orderBy('o.date');

        return $queryBuilder->getQuery()->getResult();
    }

    public function sumAmountByAccount(array $accountIds) {
        $queryBuilder = $this->_em->createQueryBuilder();
        $queryBuilder->select('a.id, SUM(o.amount) AS balance')
           ->from('AppBundle:Operation', 'o')
           ->leftJoin('o.account', 'a')
           ->where('a.id IN (:accounts)')
           ->groupBy('o.account')
           ->setParameter('accounts', $accountIds);
        return $queryBuilder->getQuery()->getResult();
    }

    public function sumAmountByTag(\DateTime $startDate, \DateTime $endDate) {
        $queryBuilder = $this->_em->createQueryBuilder();
        $queryBuilder->select('t.id AS tag, SUM(o.amount) AS amount')
            ->from('AppBundle:Operation', 'o')
            ->leftJoin('o.tag', 't')
            ->where('o.date >= :startDate')
            ->andWhere('o.date < :endDate')
            ->groupBy('t')
            ->setParameter('startDate', $startDate)
            ->setParameter('endDate', $endDate);
        $results = $queryBuilder->getQuery()->getResult();
        return array_combine(array_column($results, 'tag'), array_column($results, 'amount'));
    }

    public function sumAmountByPeriod(User $user, string $period = null, array $accountIds = null, array $tagIds = null) {
        $queryBuilder = $this->_em->createQueryBuilder();
        $queryBuilder->select('SUM(o.amount) AS amount')
            ->from('AppBundle:Operation', 'o')
            ->leftJoin('o.account', 'a')
            ->where('a.user = :user')
            ->setParameter('user', $user)
            ->orderBy('date')
            ->groupBy('date');

        if ($accountIds && count($accountIds) > 0) {
            $queryBuilder->andWhere('a.id in (:accounts)');
            $queryBuilder->setParameter('accounts', $accountIds);
        }

        if ($tagIds && count($tagIds) > 0) {
            $queryBuilder->leftJoin('o.tag', 't');
            $queryBuilder->andWhere('t.id in (:tags)');
            $queryBuilder->setParameter('tags', $tagIds);
        }

        switch ($period) {
            case 'year':
            case 'YEAR':
                $queryBuilder->addSelect("DATE_FORMAT(o.date, '%Y-01-01') AS date");
                break;

            case 'quarter':
            case 'QUARTER':
                $queryBuilder->addSelect("CONCAT(YEAR(o.date), '-', LPAD((QUARTER(o.date) * 3 - 2), 2, '0'), '-01') AS date");
                break;

            case 'month':
            case 'MONTH':
            default:
                $queryBuilder->addSelect("DATE_FORMAT(o.date, '%Y-%m-01') AS date");
                break;
        }
        
        return $queryBuilder->getQuery()->getResult();
    }
}
