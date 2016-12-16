<?php

namespace AppBundle\Action;

use AppBundle\Entity\OperationChartData;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\Routing\Annotation\Route;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Manager\OperationManager;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class OperationChartDataGet
{
    private $operationManager;
    private $token;
    private $logger;

    public function __construct(OperationManager $operationManager, TokenStorageInterface $token, LoggerInterface $logger)
    {
        $this->operationManager = $operationManager;
        $this->token = $token;
        $this->logger = $logger;
    }

    /**
     * @Route(
     *     name="operation_chart_data_get",
     *     path="/operation_chart_datas",
     *     defaults={"_api_resource_class"=OperationChartData::class, "_api_collection_operation_name"="get"}
     * )
     * @Method("GET")
     */
    public function __invoke(Request $request)
    {
        $user = $this->token->getToken()->getUser();

        $operationChartDatas = $this->operationManager->getOperationChartDatas($user, $request->query->get('period'), $request->query->get('accounts'), $request->query->get('tags'));

        if ($request->getRequestFormat() == 'jsonld') {
            $request->setRequestFormat('json');
        }
        return $operationChartDatas;
    }
}