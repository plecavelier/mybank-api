<?php

namespace AppBundle\Repository;

/**
 * TagRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class TagBudgetRepository extends \Doctrine\ORM\EntityRepository
{
    public function findBudgetByTag(int $year) {
        $queryBuilder = $this->_em->createQueryBuilder();
        $queryBuilder->select('t.id AS tag, tb.amount AS budget')
            ->from('AppBundle:TagBudget', 'tb')
            ->leftJoin('tb.tag', 't')
            ->where('tb.year = :year')
            ->groupBy('t')
            ->setParameter('year', $year);
        $results = $queryBuilder->getQuery()->getResult();
        return array_combine(array_column($results, 'tag'), array_column($results, 'budget'));
    }
}
