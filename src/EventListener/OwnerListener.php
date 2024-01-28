<?php

namespace App\EventListener;

use App\Entity\OwnedEntityInterface;
use App\Service\OwnerService;
use Doctrine\Bundle\DoctrineBundle\Attribute\AsDoctrineListener;
use Doctrine\ORM\Events;
use Doctrine\ORM\Event\PrePersistEventArgs;

#[AsDoctrineListener(event: Events::prePersist, priority: 500, connection: 'default')]
final class OwnerListener
{
    public function __construct(
        private readonly OwnerService $ownerService
    ) {
        
    }

    public function prePersist(PrePersistEventArgs $prePersistEventArgs): void
    {
        $entity = $prePersistEventArgs->getObject();

        if ($entity instanceof OwnedEntityInterface && $entity->getOwner() === null) {
            $this->ownerService->setOwner($entity);
        }
    }
}