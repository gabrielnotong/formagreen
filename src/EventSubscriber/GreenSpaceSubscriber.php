<?php

declare(strict_types=1);

namespace App\EventSubscriber;

use App\Event\GreenSpaceCreatedEvent;
use App\Service\Geolocation;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class GreenSpaceSubscriber implements EventSubscriberInterface
{
    private Geolocation $geolocation;

    public function __construct(Geolocation $geolocation) {
        $this->geolocation = $geolocation;
    }
    
    public static function getSubscribedEvents(): array
    {
        return [
            GreenSpaceCreatedEvent::class => 'onGreenSpaceCreated',
        ];
    }

    public function onGreenSpaceCreated(GreenSpaceCreatedEvent $event)
    {
        $greenSpace = $event->getGreenSpace();

        $coordinates = $this->geolocation->generateCoordinates($greenSpace);

        $greenSpace->setLatitude($coordinates['lat']);
        $greenSpace->setLongitude($coordinates['long']);
    }
}
