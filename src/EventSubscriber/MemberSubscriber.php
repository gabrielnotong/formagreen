<?php

declare(strict_types=1);

namespace App\EventSubscriber;

use App\Entity\User;
use App\Event\TrainingCenterRegisterEvent;
use App\Event\UserLambdaRegisterEvent;
use App\Repository\RoleRepository;
use App\Service\QRCodeGenerator;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class MemberSubscriber implements EventSubscriberInterface
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
    
    public static function getSubscribedEvents(): array
    {
        return [
            UserLambdaRegisterEvent::class => 'onUserLambdaRegister',
            TrainingCenterRegisterEvent::class => 'onTrainingCenterRegister',
        ];
    }

    /**
     * @throws Exception
     */
    public function onUserLambdaRegister(UserLambdaRegisterEvent $event)
    {
        $member = $event->getUserLambda();

        $this->updateMember($member);

        $qrCode = $this->qrCodeGenerator->forUserLambda($member);
        $member->setQrCode($qrCode);

        $this->manager->persist($member);
        $this->manager->flush();
    }

    /**
     * @throws Exception
     */
    public function onTrainingCenterRegister(TrainingCenterRegisterEvent $event)
    {
        $member = $event->getTrainingCenter();

        $this->updateMember($member);

        $qrCode = $this->qrCodeGenerator->forTrainingCenter($member);

        $member->setQrCode($qrCode);

        $this->manager->persist($member);
        $this->manager->flush();
    }

    /**
     * @throws Exception
     */
    public function updateMember(User $user)
    {
        $password = $this->encoder->encodePassword($user, $user->getHash());

        $role = $this->roleRepository->findOneBy(['name' => 'ROLE_MEMBER']);

        $user->setHash($password)
            ->setStartsAt(new DateTime())
            ->setEndsAt(new DateTime(sprintf(User::ADD_MONTHS, $user->getNumberOfMonths())))
            ->addUserRole($role)
        ;
    }
}
