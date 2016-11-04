<?php

namespace AppBundle\Security;

use AppBundle\Entity\Tag;
use AppBundle\Entity\User;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\HttpFoundation\Request;
use Psr\Log\LoggerInterface;

class TagVoter extends Voter
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

        if (!$subject instanceof Tag) {
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

        $tag = $subject;

        switch ($attribute) {
            case Request::METHOD_GET:
                return $this->canGet($tag, $user);
            case Request::METHOD_POST:
                return $this->canPost($tag, $user);
            case Request::METHOD_DELETE:
                return $this->canDelete($tag, $user);
            case Request::METHOD_PUT:
                return $this->canPut($tag, $user);
        }

        throw new \LogicException('This code should not be reached!');
    }

    private function canGet(Tag $tag, User $user)
    {
    	return $this->can($tag, $user);
    }

    private function canPost(Tag $tag, User $user)
    {
    	return $this->can($tag, $user);
    }

    private function canDelete(Tag $tag, User $user)
    {
    	return $this->can($tag, $user);
    }

    private function canPut(Tag $tag, User $user)
    {
    	return $this->can($tag, $user);
    }

    private function can(Tag $tag, User $user)
    {
    	return $tag->getUser() === $user;
    }
}