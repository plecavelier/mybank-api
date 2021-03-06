<?php

namespace AppBundle\EventSubscriber;

use AppBundle\Entity\User;
use AppBundle\Entity\Account;
use AppBundle\Entity\Tag;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Event\GetResponseForControllerResultEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Psr\Log\LoggerInterface;

final class SetUserSubscriber implements EventSubscriberInterface
{
    private $token;
    private $logger;

    public function __construct(TokenStorageInterface $token, LoggerInterface $logger)
    {
        $this->token = $token;
        $this->logger = $logger;
    }

    public static function getSubscribedEvents()
    {
        return [
            KernelEvents::VIEW => ['setUser', 100],
        ];
    }

    public function setUser(GetResponseForControllerResultEvent $event)
    {
        $result = $event->getControllerResult();
        $method = $event->getRequest()->getMethod();
        $user = $this->token->getToken()->getUser();

        if ($user instanceof User && $method === Request::METHOD_POST) {
            if ($result instanceof Account) {
                $result->setUser($user);
            } elseif ($result instanceof Tag) {
                $result->setUser($user);
            }
        }
    }
}
