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

final class SetBalanceSubscriber implements EventSubscriberInterface
{
    private $accountManager;
    private $token;
    private $logger;

    public function __construct(AccountManager $accountManager, TokenStorageInterface $token, LoggerInterface $logger)
    {
        $this->accountManager = $accountManager;
        $this->token = $token;
        $this->logger = $logger;
    }

    public static function getSubscribedEvents()
    {
        return [
            KernelEvents::VIEW => ['setBalance', 30],
        ];
    }

    public function setBalance(GetResponseForControllerResultEvent $event)
    {
        $result = $event->getControllerResult();
        $method = $event->getRequest()->getMethod();
        $user = $this->token->getToken()->getUser();

        $balances = null;
        if ($result instanceof Paginator || is_array($result)) {
            $this->accountManager->completeBalances($result);
        } elseif ($result instanceof Account) {
            $this->accountManager->completeBalances([$result]);
        }
    }
}
