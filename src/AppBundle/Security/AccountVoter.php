<?php

namespace AppBundle\Security;

use AppBundle\Entity\Account;
use AppBundle\Entity\User;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\HttpFoundation\Request;
use Psr\Log\LoggerInterface;

class AccountVoter extends Voter
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

        if (!$subject instanceof Account) {
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

        $account = $subject;

        switch ($attribute) {
            case Request::METHOD_GET:
                return $this->canGet($account, $user);
            case Request::METHOD_POST:
                return $this->canPost($account, $user);
            case Request::METHOD_DELETE:
                return $this->canDelete($account, $user);
            case Request::METHOD_PUT:
                return $this->canPut($account, $user);
        }

        throw new \LogicException('This code should not be reached!');
    }

    private function canGet(Account $account, User $user)
    {
    	return $this->can($account, $user);
    }

    private function canPost(Account $account, User $user)
    {
    	return $this->can($account, $user);
    }

    private function canDelete(Account $account, User $user)
    {
    	return $this->can($account, $user);
    }

    private function canPut(Account $account, User $user)
    {
    	return $this->can($account, $user);
    }

    private function can(Account $account, User $user)
    {
    	return $account->getUser() === $user;
    }
}