<?php

namespace AppBundle\Security;

use AppBundle\Entity\Tag;
use AppBundle\Entity\User;

class TagVoter extends AbstractResourceVoter
{
    protected function instanceOf($resource)
    {
        return $resource instanceof Tag;
    }

    protected function voteOnMethod($method, $tag, User $user)
    {
        return $tag->getUser() === $user;
    }
}
