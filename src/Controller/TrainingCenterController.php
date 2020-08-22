<?php
declare(strict_types=1);

namespace App\Controller;

use App\Entity\Role;
use App\Entity\TrainingCenter;
use App\Entity\UserLambda;
use App\Form\TrainingCenterRegisterType;
use App\Repository\RoleRepository;
use Doctrine\ORM\EntityManagerInterface;
use Endroid\QrCode\Factory\QrCodeFactoryInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class TrainingCenterController extends AbstractController
{
    /**
     * @Route("/training/register", name="training_account_register")
     */
    public function register(
        Request $request,
        EntityManagerInterface $manager,
        UserPasswordEncoderInterface $encoder,
        QrCodeFactoryInterface $qrCodeFactory,
        RoleRepository $roleRepository
    ): Response {
        $tc = new TrainingCenter();

        $form = $this->createForm(TrainingCenterRegisterType::class, $tc);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // todo: use listeners to manage password, registration period and qrcode
            $password = $encoder->encodePassword($tc, $tc->getHash());
            $role = $roleRepository->findOneBy(['name' => 'ROLE_MEMBER']);

            $tc->setHash($password)
                ->setStartsAt(new \DateTime())
                ->setEndsAt(new \DateTime(sprintf(UserLambda::ADD_MONTHS, $tc->getNumberOfMonths())))
                ->addUserRole($role)
            ;

            $qrCode = $qrCodeFactory->create(sprintf(
                TrainingCenter::QRCODE_CONTENT,
                $tc->getName(),
                $tc->getEmail(),
                $tc->getStartsAt()->format('Y-m-d'),
                $tc->getEndsAt()->format('Y-m-d'),
                $tc->getAddress(),
                $tc->getCountry(),
                $tc->getCity(),
                $tc->getPhoneNumber()
            ));

            $tc->setQrCode($qrCode->getText());

            $manager->persist($tc);
            $manager->flush();

            $this->addFlash(
                'success',
                'Your account has been successfully created. You can now log in'
            );

            return $this->redirectToRoute('account_login');
        }

        return $this->render('pub/account/registration_training_center.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/training/profile", name="training_account_profile")
     * @IsGranted("ROLE_USER")
     */
    public function profile(Request $request, EntityManagerInterface $manager): Response
    {
        $user = $this->getUser();

        $form = $this->createForm(TrainingCenterRegisterType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $manager->persist($user);
            $manager->flush();

            $this->addFlash(
                'success',
                'Your profile has been successfully updated'
            );
        }

        return $this->render('pub/account/profile_training_center.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
