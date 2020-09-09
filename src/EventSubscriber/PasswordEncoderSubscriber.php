<?php

declare(strict_types=1);

namespace App\EventSubscriber;

use App\Entity\User;
use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Events;
use Exception;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class PasswordEncoderSubscriber implements EventSubscriber
{
    private UserPasswordEncoderInterface $encoder;

    public function __construct(UserPasswordEncoderInterface $encoder) {
        $this->encoder = $encoder;
    }
    
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

        if (!$member->getPlainTextPassword()) {
            return;
        }

        $hash = $this->encoder->encodePassword($member, $member->getPlainTextPassword());
        $member->setHash($hash);
    }
}
