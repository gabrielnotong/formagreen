<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\GreenSpace;
use App\Event\GreenSpaceCreatedEvent;
use App\Form\GreenSpaceType;
use App\Repository\GreenSpaceRepository ;
use App\Service\Paginator;
use Doctrine\ORM\EntityManagerInterface;
use Psr\EventDispatcher\EventDispatcherInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminGreenSpaceController extends AbstractController
{
    /**
     * @Route("/admin/greens/{page<\d+>?1}", name="admin_green_spaces_index")
     */
    public function index(int $page, Paginator $paginator, GreenSpaceRepository $repository)
    {
        $paginator->setCurrentPage($page)
            ->setQuery($repository->findAllQuery());

        return $this->render('admin/greenspace/index.html.twig', [
            'paginator' => $paginator,
        ]);
    }

    /**
     * @Route("/admin/greens/{id}/show", name="admin_green_spaces_show")
     */
    public function show(GreenSpace $greenSpace)
    {
        return $this->render('admin/greenspace/show.html.twig', [
            'greenSpace' => $greenSpace,
        ]);
    }

    /**
     * @Route("/admin/greens/create", name="admin_green_spaces_create", methods={"POST", "GET"})
     */
    public function create(Request $request, EntityManagerInterface $manager, EventDispatcherInterface $eventDispatcher)
    {
        $greenSpace = new GreenSpace();

        $form = $this->createForm(GreenSpaceType::class, $greenSpace);

        $form->handleRequest($request);

        $eventDispatcher->dispatch(new GreenSpaceCreatedEvent($greenSpace));

        if ($form->isSubmitted() && $form->isValid()) {
            $manager->persist($greenSpace);
            $manager->flush();

            $this->addFlash(
                'success',
                'GreenSpace successfully created'
            );

            return $this->redirectToRoute('admin_green_spaces_index');
        }

        return $this->render('admin/greenspace/add.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/admin/greens/{id}/edit", name="admin_green_spaces_edit", methods={"POST", "GET"})
     */
    public function edit(GreenSpace $greenSpace, Request $request, EntityManagerInterface $manager)
    {
        $form = $this->createForm(GreenSpaceType::class, $greenSpace);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $manager->persist($greenSpace);
            $manager->flush();

            $this->addFlash(
                'success',
                'GreenSpace successfully Updated'
            );

            return $this->redirectToRoute('admin_green_spaces_index');
        }

        return $this->render('admin/greenspace/edit.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/admin/greens/{id}/delete", name="admin_green_spaces_delete", methods={"DELETE"})
     */
    public function delete(GreenSpace $greenSpace, EntityManagerInterface $manager, Request $request): Response
    {
        if ($this->isCsrfTokenValid('delete' . $greenSpace->getId(), $request->get('_token'))) {
            $manager->remove($greenSpace);
            $manager->flush();
            $this->addFlash('success', 'GreenSpace successfully deleted');
        }

        $referer = $request->headers->get('referer');

        return $this->redirect($referer);
    }
}
