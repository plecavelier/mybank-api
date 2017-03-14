<?php

namespace AppBundle\EventSubscriber;

use AppBundle\Entity\Account;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Event\GetResponseForControllerResultEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Paginator;
use Psr\Log\LoggerInterface;
use AppBundle\Manager\AccountManager;

final class CreateFirstOperationSubscriber implements EventSubscriberInterface
{
    private $accountManager;
    private $logger;

    public function __construct(AccountManager $accountManager, LoggerInterface $logger)
    {
        $this->accountManager = $accountManager;
        $this->logger = $logger;
    }

    public static function getSubscribedEvents()
    {
        return [
            KernelEvents::VIEW => ['createFirstOperation', 30],
        ];
    }

    public function createFirstOperation(GetResponseForControllerResultEvent $event)
    {
        $result = $event->getControllerResult();
        $method = $event->getRequest()->getMethod();

        if ($result instanceof Account && $method === Request::METHOD_POST) {
            $this->accountManager->createFirstOperation($result);
        }
    }
}
