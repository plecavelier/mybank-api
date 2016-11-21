<?php

namespace AppBundle\Action;

use AppBundle\Entity\OperationYearMonth;
use Doctrine\Common\Persistence\ManagerRegistry;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\Routing\Annotation\Route;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\SerializerInterface;
use AppBundle\Manager\OperationManager;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class OperationYearMonthGet
{
    private $operationManager;
    private $serializer;
    private $token;
    private $logger;

    public function __construct(OperationManager $operationManager, SerializerInterface $serializer, TokenStorageInterface $token, LoggerInterface $logger)
    {
        $this->operationManager = $operationManager;
        $this->serializer = $serializer;
        $this->token = $token;
        $this->logger = $logger;
    }

    /**
     * @Route(
     *     name="operation_year_month_get",
     *     path="/operation_year_months",
     *     defaults={"_api_resource_class"=OperationYearMonth::class, "_api_collection_operation_name"="get"}
     * )
     * @Method("GET")
     */
    public function __invoke(Request $request)
    {
        $user = $this->token->getToken()->getUser();
        $operationYearMonths = $this->operationManager->getAllOperationYearMonths($user);
        if ($request->getRequestFormat() == 'jsonld') {
            $request->setRequestFormat('json');
        }
        return $operationYearMonths;
    }
}