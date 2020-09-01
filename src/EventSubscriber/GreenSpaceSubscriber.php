<?php

declare(strict_types=1);

namespace App\EventSubscriber;

use App\Entity\GreenSpace;
use App\Service\Geolocation;
use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Events;

class GreenSpaceSubscriber implements EventSubscriber
{
    private Geolocation $geolocation;

    public function __construct(Geolocation $geolocation) {
        $this->geolocation = $geolocation;
    }
    
    public function getSubscribedEvents(): array
    {
        return [
            Events::prePersist => 'prePersist',
            Events::preUpdate => 'preUpdate',
        ];
    }

    public function prePersist(LifecycleEventArgs $event)
    {
        $this->mutateData($event);
    }

    public function preUpdate(LifecycleEventArgs $event)
    {
        $this->mutateData($event);
    }

    private function mutateData(LifecycleEventArgs $event)
    {
        $greenSpace = $event->getObject();

        if (!$greenSpace instanceof GreenSpace) {
            return;
        }

        $coordinates = $this->geolocation->generateCoordinates($greenSpace);

        $greenSpace->setLatitude($coordinates['lat']);
        $greenSpace->setLongitude($coordinates['long']);
    }
}
