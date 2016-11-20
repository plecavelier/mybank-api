<?php

namespace AppBundle\Action;

use AppBundle\Entity\Operation;
use Doctrine\Common\Persistence\ManagerRegistry;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\Routing\Annotation\Route;
use Psr\Log\LoggerInterface;

class OperationsFirstLast
{
    private $logger;

    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    /**
     * @Route(
     *     name="operations_first_last",
     *     path="/first_last",
     *     defaults={"_api_resource_class"=Operation::class, "_api_collection_operation_name"="first_last"}
     * )
     * @Method("GET")
     */
    public function __invoke($data)
    {
        $this->logger->info($data);
        return $data;
    }
}