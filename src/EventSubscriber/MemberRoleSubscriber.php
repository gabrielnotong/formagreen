<?php

declare(strict_types=1);

namespace App\EventSubscriber;

use App\Entity\Role;
use App\Entity\User;
use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Events;
use Exception;

class MemberRoleSubscriber implements EventSubscriber
{
    public function getSubscribedEvents(): array
    {
        return [
            Events::prePersist => 'prePersist',
        ];
    }

    /**
     * @throws Exception
     */
    public function prePersist(LifecycleEventArgs $event): void
    {
        $member = $event->getObject();

        if (!$member instanceof User) {
            return;
        }

        $roleRepository = $event->getEntityManager()->getRepository(Role::class);
        $role = $roleRepository->findOneBy(['name' => 'ROLE_MEMBER']);

        $member->addUserRole($role);
    }
}
