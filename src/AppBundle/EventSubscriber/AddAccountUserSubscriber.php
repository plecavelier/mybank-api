<?php

namespace AppBundle\EventSubscriber;

use AppBundle\Entity\Account;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Event\GetResponseForControllerResultEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

final class AddAccountUserSubscriber implements EventSubscriberInterface
{
    private $token;

    public function __construct(TokenStorageInterface $token)
    {
        $this->token = $token;
    }

    public static function getSubscribedEvents()
    {
        return [
            KernelEvents::VIEW => ['addUser', 100],
        ];
    }

    public function addUser(GetResponseForControllerResultEvent $event)
    {
        $account = $event->getControllerResult();
        $method = $event->getRequest()->getMethod();

        if (!$account instanceof Account || Request::METHOD_POST !== $method) {
            return;
        }

        $user = $this->token->getToken()->getUser();
        $account->setUser($user);
    }
}
