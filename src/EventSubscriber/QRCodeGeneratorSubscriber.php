<?php

declare(strict_types=1);

namespace App\EventSubscriber;

use App\Entity\User;
use App\Service\QRCodeGenerator;
use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Events;
use Exception;

class QRCodeGeneratorSubscriber implements EventSubscriber
{
    private QRCodeGenerator $qrCodeGenerator;

    public function __construct(QRCodeGenerator $qrCodeGenerator) {
        $this->qrCodeGenerator = $qrCodeGenerator;
    }
    
    public function getSubscribedEvents(): array
    {
        return [
            Events::prePersist => 'prePersist',
            Events::preUpdate => 'preUpdate',
        ];
    }

    /**
     * @throws Exception
     */
    public function prePersist(LifecycleEventArgs $event)
    {
        $this->updateData($event);
    }

    /**
     * @throws Exception
     */
    public function preUpdate(LifecycleEventArgs $event): void
    {
        $this->updateData($event);
    }

    /**
     * @throws Exception
     */
    public function updateData(LifecycleEventArgs $event): void
    {
        $member = $event->getObject();

        if (!$member instanceof User) {
            return;
        }

        $qrCode = $this->qrCodeGenerator->forUser($member);
        $member->setQrCode($qrCode);
    }
}
