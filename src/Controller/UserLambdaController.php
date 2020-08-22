<?php
declare(strict_types=1);

namespace App\Controller;

use App\Entity\UserLambda;
use App\Event\UserLambdaRegisterEvent;
use App\Form\UserLambdaProfileType;
use App\Form\UserLambdaRegisterType;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Psr\EventDispatcher\EventDispatcherInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class UserLambdaController extends AbstractController
{
    /**
     * UserPasswordEncoderInterface is used in order to tell symfony which algorithm to use (security.yml)
     * @Route("/register", name="user_lambda_register")
     * @throws Exception
     */
    public function register(Request $request, EventDispatcherInterface $eventDispatcher): Response {
        $user = new UserLambda();

        $form = $this->createForm(UserLambdaRegisterType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $eventDispatcher->dispatch(new UserLambdaRegisterEvent($user));

            $this->addFlash(
                'success',
                'Your account has been successfully created. You can now log in'
            );

            return $this->redirectToRoute('account_login');
        }

        return $this->render('pub/userlambda/registration.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/account/profile", name="user_lambda_profile")
     * @IsGranted("ROLE_USER")
     */
    public function profile(Request $request, EntityManagerInterface $manager): Response
    {
        $user = $this->getUser();

        $form = $this->createForm(UserLambdaProfileType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $manager->persist($user);
            $manager->flush();

            $this->addFlash(
                'success',
                'Your profile has been successfully updated'
            );
        }

        return $this->render('pub/userlambda/profile.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/account/me", name="user_lambda_me")
     * @IsGranted("ROLE_USER")
     */
    public function me(): Response
    {
        return $this->render('pub/userlambda/show.html.twig', [
            'member' => $this->getUser(),
        ]);
    }
}
