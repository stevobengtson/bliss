<?php

namespace App\Controller;

use App\Entity\User;
use Symfony\Component\Security\Core\Exception\UserNotFoundException;

trait UserTrait
{
    public function getCurrentUser(): User
    {
        /** @var User|null $user */
        $user = $this->getUser();
        if (null === $user) {
            throw new UserNotFoundException();
        }

        return $user;
    }
}