<?php

declare(strict_types=1);

namespace App\EventSubscriber;

use App\Entity\User;
use DateTime;
use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Events;
use Exception;

class DateStartEndSubscriber implements EventSubscriber
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

        $member
            ->setStartsAt(new DateTime())
            ->setEndsAt(new DateTime(sprintf(User::ADD_MONTHS, $member->getNumberOfMonths())));
    }
}
