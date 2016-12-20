<?php

namespace AppBundle\Manager;

use Doctrine\ORM\EntityManager;
use AppBundle\Entity\User;
use AppBundle\Entity\OperationChartData;
use AppBundle\Entity\OperationYearMonth;
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

    public function getOperationYearMonths(User $user): array {
        $result = $this->em->getRepository('AppBundle:Operation')->findAllYearMonthTuples($user);

        $operationYearMonths = array_map(function($row) {
            return new OperationYearMonth($row['year'], $row['month']);
        }, $result);

        return $operationYearMonths;
    }

    public function getOperationChartDatas(User $user, string $period = null, string $accountsParam = null, string $tagsParam = null): array {
        
        $accountIds = null;
        $accountsParam = preg_replace('# +#', '', $accountsParam);
        if ($accountsParam != '') {
            $accountIds = explode(',', $accountsParam);
        }

        $tagIds = null;
        $tagsParam = preg_replace('# +#', '', $tagsParam);
        if ($tagsParam != '') {
            $tagIds = explode(',', $tagsParam);
        }

        $result = $this->em->getRepository('AppBundle:Operation')->sumAmountByPeriod($user, $period, $accountIds, $tagIds);

        $operationChartDatas = array_map(function($row) {
            return new OperationChartData(new \DateTime($row['date']), $row['amount']);
        }, $result);

        return $operationChartDatas;
    }

    public function import(string $format, string $content) {

        // TODO : utiliser format avec design pattern factory pour gérer différents formats
        $operations = $this->parseOfx($content);
    }

    private function parseOfx(string $content): array {
        // TODO : utiliser code existant
        return [];
    }
}