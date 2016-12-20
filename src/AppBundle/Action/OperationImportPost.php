<?php

namespace AppBundle\Action;

use AppBundle\Entity\OperationImport;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\Routing\Annotation\Route;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use AppBundle\Manager\OperationManager;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class OperationImportPost
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
     *     name="operation_import_post",
     *     path="/operation_imports",
     *     defaults={"_api_resource_class"=OperationImport::class, "_api_collection_operation_name"="post"}
     * )
     * @Method("POST")
     */
    public function __invoke(Request $request, $data)
    {
    	$this->operationManager->import($data->getFormat(), $data->getContent());
    	return new Response("");
    }
}