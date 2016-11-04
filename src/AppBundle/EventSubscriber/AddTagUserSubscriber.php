<?php

namespace AppBundle\EventSubscriber;

use AppBundle\Entity\Tag;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Event\GetResponseForControllerResultEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

final class AddTagUserSubscriber implements EventSubscriberInterface
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
        $tag = $event->getControllerResult();
        $method = $event->getRequest()->getMethod();

        if (!$tag instanceof Tag || Request::METHOD_POST !== $method) {
            return;
        }

        $user = $this->token->getToken()->getUser();
        $tag->setUser($user);
    }
}
