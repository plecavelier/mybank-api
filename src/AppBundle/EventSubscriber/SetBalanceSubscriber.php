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
use AppBundle\Manager\OperationManager;

final class SetBalanceSubscriber implements EventSubscriberInterface
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

    public static function getSubscribedEvents()
    {
        return [
            KernelEvents::VIEW => ['setBalance', 80],
        ];
    }

    public function setBalance(GetResponseForControllerResultEvent $event)
    {
        $result = $event->getControllerResult();
        $method = $event->getRequest()->getMethod();
        $user = $this->token->getToken()->getUser();

        $balances = null;
        if ($result instanceof Paginator || is_array($result)) {
            foreach ($result as $item) {
                if ($item instanceof Account) {
                    if ($balances == null) {
                        $balances = $this->operationManager->getAccountBalances($user);
                    }
                    if (isset($balances[$item->getId()])) {
                        $item->setBalance($balances[$item->getId()]);
                    }
                }
            }
        } elseif ($result instanceof Account) {
            $balances = $this->operationManager->getAccountBalances($user, $result);            
            if (isset($balances[$result->getId()])) {
                $result->setBalance($balances[$result->getId()]);
            }
        }
    }
}
