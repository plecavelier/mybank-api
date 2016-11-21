<?php

namespace AppBundle\Repository;

use AppBundle\Entity\User;
use AppBundle\Entity\OperationYearMonth;

/**
 * OperationRepository
 */
class OperationRepository extends \Doctrine\ORM\EntityRepository
{

    public function findAllOperationYearMonths(User $user) {
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
}
