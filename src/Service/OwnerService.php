<?php

namespace App\Service;

use App\Entity\OwnedEntityInterface;
use App\Entity\User;
use App\Exception\NotLoggedInException;
use Symfony\Bundle\SecurityBundle\Security;

class OwnerService
{
    public function __construct(private readonly Security $security)
    {
    }

    /**
     * @param OwnedEntityInterface $entity
     * @return void
     * @throws NotLoggedInException
     */
    public function setOwner(OwnedEntityInterface $entity): void
    {
        /** @var User $user */
        $user = $this->security->getUser();
        if (null === $user) {
            throw new NotLoggedInException(403);
        }
        $entity->setOwner($user);
    }
}
