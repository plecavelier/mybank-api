<?php

namespace AppBundle\Manager;

use Doctrine\ORM\EntityManager;
use AppBundle\Entity\User;
use AppBundle\Entity\OperationChartData;
use AppBundle\Entity\OperationYearMonth;
use Psr\Log\LoggerInterface;
use AppBundle\Entity\Account;
use AppBundle\Entity\Operation;
use AppBundle\Manager\AccountManager;

class OperationManager
{
    private $em;
    private $logger;
    private $accountManager;

    public function __construct(EntityManager $em, LoggerInterface $logger, AccountManager $accountManager)
    {
        $this->em = $em;
        $this->logger = $logger;
        $this->accountManager = $accountManager;
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

        foreach ($operations as $operation) {
            $this->em->persist($operation);
        }
        $this->em->flush();
    }

    private function parseOfx(string $content): array {
        $operations = [];

        $ofxParser = new \OfxParser\Parser();
        $ofx = $ofxParser->loadFromString($content);
        foreach ($ofx->bankAccounts as $bankAccount) {

            $account = $this->accountManager->getByNumber($bankAccount->accountNumber);
            if ($account == null) {
                throw new \Exception('Account with number "'.$bankAccount->accountNumber.'" not found');
            }

            foreach ($bankAccount->statement->transactions as $transaction) {
                $operation = new Operation();
                $operation->setName($transaction->name);
                $operation->setDescription($transaction->memo);
                $operation->setDate($transaction->date);
                $operation->setAmount(round($transaction->amount * 100));
                $operation->setAccount($account);
                $operations[] = $operation;
            }
        }
        return $operations;
    }
}