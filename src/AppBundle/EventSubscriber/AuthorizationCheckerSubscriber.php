<?php

namespace AppBundle\EventSubscriber;

use AppBundle\Entity\Account;
use AppBundle\Entity\Tag;
use AppBundle\Entity\Operation;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Event\GetResponseForControllerResultEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Paginator;
use Psr\Log\LoggerInterface;

final class AuthorizationCheckerSubscriber implements EventSubscriberInterface
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
            KernelEvents::VIEW => ['checkAuthorization', 80],
        ];
    }

    public function checkAuthorization(GetResponseForControllerResultEvent $event)
    {
        $result = $event->getControllerResult();
        $method = $event->getRequest()->getMethod();

        if ($result instanceof Paginator) {
            foreach ($result as $item) {
                if ($this->isApiResource($item) && !$this->authorizationChecker->isGranted($method, $item)) {
                    throw new AccessDeniedException();
                }
            }
        } elseif ($this->isApiResource($result) && !$this->authorizationChecker->isGranted($method, $result)) {
            throw new AccessDeniedException();
        }
    }

    private function isApiResource($resource) {
        return $resource instanceof Account
            || $resource instanceof Tag
            || $resource instanceof Operation;
    }
}
