<?php

namespace App\EventListener;

use App\Entity\TrackedEntityInterface;
use Doctrine\Bundle\DoctrineBundle\Attribute\AsDoctrineListener;
use Doctrine\ORM\Events;
use Doctrine\ORM\Event\PrePersistEventArgs;
use Doctrine\ORM\Event\PreUpdateEventArgs;

#[AsDoctrineListener(event: Events::prePersist, priority: 500, connection: 'default')]
#[AsDoctrineListener(event: Events::preUpdate, priority: 500, connection: 'default')]
final class TrackedListener
{
    public function prePersist(PrePersistEventArgs $prePersistEventArgs): void
    {
        $entity = $prePersistEventArgs->getObject();

        if ($entity instanceof TrackedEntityInterface) {
            if (null === $entity->getCreatedAt()) {
                $entity->setCreatedAt(new \DateTimeImmutable());
            }

            if (null === $entity->getUpdatedAt()) {
                $entity->setUpdatedAt(new \DateTimeImmutable());
            }
        }
    }

    public function preUpdate(PreUpdateEventArgs $preUpdateEventArgs): void
    {
        $entity = $preUpdateEventArgs->getObject();

        if ($entity instanceof TrackedEntityInterface) {
            if (null === $entity->getUpdatedAt()) {
                $entity->setUpdatedAt(new \DateTimeImmutable());
            }
        }
    }
}
