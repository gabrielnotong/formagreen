<?php
declare(strict_types=1);

namespace App\Controller;

use App\Entity\PasswordReset;
use App\Entity\UserLambda;
use App\Form\PasswordResetType;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class AccountController extends AbstractController
{
    /**
     * Allows user to login.
     * Symfony handles it automatically using configurations in security.yml > firewall:main:provider/form_login
     * @Route("/login", name="account_login")
     */
    public function login(AuthenticationUtils $utils): Response
    {
        $error    = $utils->getLastAuthenticationError();
        $username = $utils->getLastUsername();

        return $this->render('pub/account/login.html.twig', [
            'hasError'     => $error != null,
            'error'        => $error != null ? $error->getMessage() : '',
            'lastUsername' => $username,
        ]);
    }

    /**
     * Allows user to logout.
     * Symfony handles it automatically using configurations in security.yml > firewall:main:logout
     * @Route("/logout", name="account_logout")
     */
    public function logout(): void
    {
    }

    /**
     * @Route("/account/password", name="account_password")
     * @IsGranted("ROLE_USER")
     */
    public function resetPassword(
        Request $request,
        EntityManagerInterface $manager,
        UserPasswordEncoderInterface $encoder
    ): Response
    {
        $passwordReset = new PasswordReset();

        $form = $this->createForm(PasswordResetType::class, $passwordReset);
        $form->handleRequest($request);

        /** @var UserLambda $user */
        $user = $this->getUser();

        if ($form->isSubmitted() && $form->isValid()) {
            if (!password_verify($passwordReset->getOld(), $user->getHash())) {
                // Adds an error to the form field `old`
                $form->get('old')->addError(new FormError('Old password is not correct'));
            } else {
                $hash = $encoder->encodePassword($user, $passwordReset->getNew());
                $user->setHash($hash);

                $manager->persist($user);
                $manager->flush();

                $this->addFlash(
                    'success',
                    'Your password have been successfully changed'
                );
            }
        }

        return $this->render('pub/account/password.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/account/me", name="account_me")
     * @IsGranted("ROLE_USER")
     */
    public function me(): Response
    {
        return $this->render('pub/member/show.html.twig', [
            'user' => $this->getUser(),
        ]);
    }
}
