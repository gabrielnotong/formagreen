<?php

declare(strict_types=1);

namespace App\EventSubscriber;

use App\Entity\User;
use App\Repository\RoleRepository;
use App\Service\QRCodeGenerator;
use DateTime;
use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Events;
use Exception;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class MemberSubscriber implements EventSubscriber
{
    private EntityManagerInterface $manager;
    private UserPasswordEncoderInterface $encoder;
    private RoleRepository $roleRepository;
    private QRCodeGenerator $qrCodeGenerator;

    public function __construct(
        EntityManagerInterface $manager,
        UserPasswordEncoderInterface $encoder,
        QRCodeGenerator $qrCodeGenerator,
        RoleRepository $roleRepository
    ) {
        $this->manager = $manager;
        $this->encoder = $encoder;
        $this->qrCodeGenerator = $qrCodeGenerator;
        $this->roleRepository = $roleRepository;
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
        $member = $event->getObject();

        if (!$member instanceof User) {
            return;
        }

        $role = $this->roleRepository->findOneBy(['name' => 'ROLE_MEMBER']);

        $member
            ->setStartsAt(new DateTime())
            ->setEndsAt(new DateTime(sprintf(User::ADD_MONTHS, $member->getNumberOfMonths())))
            ->addUserRole($role)
            ;

        $this->updateData($member);
    }

    /**
     * @throws Exception
     */
    public function preUpdate(LifecycleEventArgs $event): void
    {
        $member = $event->getObject();

        if (!$member instanceof User) {
            return;
        }

        $this->updateData($member);
    }

    /**
     * @throws Exception
     */
    public function updateData(User $user): void
    {
        $qrCode = $this->qrCodeGenerator->forUser($user);
        $user->setQrCode($qrCode);

        if (!$user->getPassword()) {
            return;
        }

        $hash = $this->encoder->encodePassword($user, $user->getPassword());
        $user->setHash($hash);
    }
//
//    /**
//     * @throws Exception
//     */
//    public function onTrainingCenterRegister(TrainingCenterRegisterEvent $event)
//    {
//        $member = $event->getTrainingCenter();
//
//        $this->updateData($member);
//
//        $qrCode = $this->qrCodeGenerator->forTrainingCenter($member);
//
//        $member->setQrCode($qrCode);
//
//        $this->manager->persist($member);
//        $this->manager->flush();
//    }
//
//    /**
//     * @throws Exception
//     */
//    public function updateData(User $user)
//    {
//        $password = $this->encoder->encodePassword($user, $user->getHash());
//
//        $role = $this->roleRepository->findOneBy(['name' => 'ROLE_MEMBER']);
//
//        $user->setHash($password)
//            ->setStartsAt(new DateTime())
//            ->setEndsAt(new DateTime(sprintf(User::ADD_MONTHS, $user->getNumberOfMonths())))
//            ->addUserRole($role)
//        ;
//    }
}
