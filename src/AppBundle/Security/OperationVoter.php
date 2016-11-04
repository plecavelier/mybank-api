<?php

namespace AppBundle\Security;

use AppBundle\Entity\Operation;
use AppBundle\Entity\User;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\HttpFoundation\Request;
use Psr\Log\LoggerInterface;

class OperationVoter extends Voter
{

    private $logger;

    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    protected function supports($attribute, $subject)
    {
        if (!in_array($attribute, array(Request::METHOD_GET, Request::METHOD_POST, Request::METHOD_DELETE, Request::METHOD_PUT))) {
            return false;
        }

        if (!$subject instanceof Operation) {
            return false;
        }

        return true;
    }

    protected function voteOnAttribute($attribute, $subject, TokenInterface $token)
    {
        $user = $token->getUser();

        if (!$user instanceof User) {
            return false;
        }

        $operation = $subject;

        switch ($attribute) {
            case Request::METHOD_GET:
                return $this->canGet($operation, $user);
            case Request::METHOD_POST:
                return $this->canPost($operation, $user);
            case Request::METHOD_DELETE:
                return $this->canDelete($operation, $user);
            case Request::METHOD_PUT:
                return $this->canPut($operation, $user);
        }

        throw new \LogicException('This code should not be reached!');
    }

    private function canGet(Operation $operation, User $user)
    {
    	return $this->can($operation, $user);
    }

    private function canPost(Operation $operation, User $user)
    {
    	return $this->can($operation, $user);
    }

    private function canDelete(Operation $operation, User $user)
    {
    	return $this->can($operation, $user);
    }

    private function canPut(Operation $operation, User $user)
    {
    	return $this->can($operation, $user);
    }

    private function can(Operation $operation, User $user)
    {
    	$this->logger->info("===== CAN =====");
    	return $operation->getAccount()->getUser() === $user
    		&& ($operation->getTag() == null || $operation->getTag()->getUser() === $user);
    }
}