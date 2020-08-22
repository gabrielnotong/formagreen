<?php
declare(strict_types=1);

namespace App\Controller;

use App\Entity\UserLambda;
use App\Form\AccountType;
use App\Form\RegistrationType;
use App\Repository\RoleRepository;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Endroid\QrCode\Factory\QrCodeFactoryInterface;
use Exception;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserLambdaController extends AbstractController
{
    /**
     * UserPasswordEncoderInterface is used in order to tell symfony which algorithm to use (security.yml)
     * @Route("/register", name="account_register")
     * @throws Exception
     */
    public function register(
        Request $request,
        EntityManagerInterface $manager,
        UserPasswordEncoderInterface $encoder,
        QrCodeFactoryInterface $qrCodeFactory,
        RoleRepository $roleRepository
    ): Response {
        $user = new UserLambda();

        $form = $this->createForm(RegistrationType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // todo: use listeners to manage password, registration period and qrcode
            $password = $encoder->encodePassword($user, $user->getHash());
            $role = $roleRepository->findOneBy(['name' => 'ROLE_MEMBER']);

            $user->setHash($password)
                ->setStartsAt(new DateTime())
                ->setEndsAt(new DateTime(sprintf(UserLambda::ADD_MONTHS, $user->getNumberOfMonths())))
                ->addUserRole($role)
            ;

            $qrCode = $qrCodeFactory->create(sprintf(
                UserLambda::QRCODE_CONTENT,
                $user->__toString(),
                $user->getEmail(),
                $user->getStartsAt()->format('Y-m-d'),
                $user->getEndsAt()->format('Y-m-d'),
                $user->getPhoneNumber()
            ));

            $user->setQrCode($qrCode->getText());

            $manager->persist($user);
            $manager->flush();

            $this->addFlash(
                'success',
                'Your account has been successfully created. You can now log in'
            );

            return $this->redirectToRoute('account_login');
        }

        return $this->render('pub/account/registration.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/account/profile", name="account_profile")
     * @IsGranted("ROLE_USER")
     */
    public function profile(Request $request, EntityManagerInterface $manager): Response
    {
        $user = $this->getUser();

        $form = $this->createForm(AccountType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $manager->persist($user);
            $manager->flush();

            $this->addFlash(
                'success',
                'Your profile has been successfully updated'
            );
        }

        return $this->render('pub/account/profile.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
