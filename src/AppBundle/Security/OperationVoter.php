<?php

namespace AppBundle\Security;

use AppBundle\Entity\Operation;
use AppBundle\Entity\User;

class OperationVoter extends AbstractResourceVoter
{
    protected function instanceOf($resource)
    {
        return $resource instanceof Operation;
    }

    protected function voteOnMethod($method, $operation, User $user)
    {
        return $operation->getAccount()->getUser() === $user
            && ($operation->getTag() == null || $operation->getTag()->getUser() === $user);
    }
}