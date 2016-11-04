<?php

namespace AppBundle\EventSubscriber;

use AppBundle\Entity\Operation;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Event\GetResponseForControllerResultEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Psr\Log\LoggerInterface;

final class OperationAccessSubscriber implements EventSubscriberInterface
{
    private $logger;
    private $authorizationChecker;

    public function __construct(LoggerInterface $logger, AuthorizationCheckerInterface $authorizationChecker)
    {
        $this->logger = $logger;
        $this->authorizationChecker = $authorizationChecker;
    }

    public static function getSubscribedEvents()
    {
        return [
            KernelEvents::VIEW => ['userAccess', 80],
        ];
    }

    public function userAccess(GetResponseForControllerResultEvent $event)
    {
        $operation = $event->getControllerResult();
        $method = $event->getRequest()->getMethod();

        if (!$operation instanceof Operation) {
            return;
        }

        if (!$this->authorizationChecker->isGranted($method, $operation)) {
            throw new AccessDeniedException();
        }
    }
}
