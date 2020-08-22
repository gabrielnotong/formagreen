<?php
declare(strict_types=1);

namespace App\Controller;

use App\Entity\TrainingCenter;
use App\Event\TrainingCenterRegisterEvent;
use App\Form\TrainingCenterProfileType;
use App\Form\TrainingCenterRegisterType;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Psr\EventDispatcher\EventDispatcherInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TrainingCenterController extends AbstractController
{
    /**
     * @Route("/training/register", name="training_center_register")
     * @throws Exception
     */
    public function register(Request $request, EventDispatcherInterface $eventDispatcher): Response {
        $tc = new TrainingCenter();

        $form = $this->createForm(TrainingCenterRegisterType::class, $tc);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $eventDispatcher->dispatch(new TrainingCenterRegisterEvent($tc));

            $this->addFlash(
                'success',
                'Your account has been successfully created. You can now log in'
            );

            return $this->redirectToRoute('account_login');
        }

        return $this->render('pub/training/registration.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/training/profile", name="training_center_profile")
     * @IsGranted("ROLE_USER")
     */
    public function profile(Request $request, EntityManagerInterface $manager): Response
    {
        $user = $this->getUser();

        $form = $this->createForm(TrainingCenterProfileType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $manager->persist($user);
            $manager->flush();

            $this->addFlash(
                'success',
                'Your profile has been successfully updated'
            );
        }

        return $this->render('pub/training/profile.html.twig', [
            'form' => $form->createView(),
        ]);
    }


    /**
     * @Route("/training/me", name="training_center_me")
     * @IsGranted("ROLE_USER")
     */
    public function me(): Response
    {
        return $this->render('pub/training/show.html.twig', [
            'member' => $this->getUser(),
        ]);
    }
}
