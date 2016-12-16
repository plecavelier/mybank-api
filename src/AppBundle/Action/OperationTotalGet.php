<?php

namespace AppBundle\Action;

use AppBundle\Entity\Operation;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class OperationTotalGet
{

    public function __construct()
    {
    }

    /**
     * @Route(
     *     name="operation_total_get",
     *     path="/operations_total",
     *     defaults={"_api_resource_class"=Operation::class, "_api_collection_operation_name"="total"}
     * )
     * @Method("GET")
     */
    public function __invoke(Request $request, $data)
    {
        if ($request->getRequestFormat() == 'jsonld') {
            $request->setRequestFormat('json');
        }
        return $data[0]['total'];
    }
}