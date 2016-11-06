<?php

namespace AppBundle\Security;

use AppBundle\Entity\Account;
use AppBundle\Entity\User;

class AccountVoter extends AbstractResourceVoter
{
    protected function instanceOf($resource)
    {
        return $resource instanceof Account;
    }

    protected function voteOnMethod($method, $account, User $user)
    {
        return $account->getUser() === $user;
    }
}