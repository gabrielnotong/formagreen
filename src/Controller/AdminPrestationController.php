<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\Prestation;
use App\Form\PrestationType;
use App\Repository\PrestationRepository ;
use App\Service\Paginator;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminPrestationController extends AbstractController
{
    /**
     * @Route("/admin/prestations/{page<\d+>?1}", name="admin_prestations_index")
     */
    public function index(int $page, Paginator $paginator, PrestationRepository $repository)
    {
        $paginator->setCurrentPage($page)
            ->setQuery($repository->findAllQuery());

        return $this->render('admin/prestation/index.html.twig', [
            'paginator' => $paginator,
        ]);
    }

    /**
     * @Route("/admin/prestations/create", name="admin_prestations_create", methods={"POST", "GET"})
     */
    public function create(Request $request, EntityManagerInterface $manager)
    {
        $greenSpace = new Prestation();

        $form = $this->createForm(PrestationType::class, $greenSpace);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $manager->persist($greenSpace);
            $manager->flush();

            $this->addFlash(
                'success',
                'Prestation successfully created'
            );

            return $this->redirectToRoute('admin_prestations_index');
        }

        return $this->render('admin/prestation/add.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/admin/prestations/{id}/edit", name="admin_prestations_edit", methods={"POST", "GET"})
     */
    public function edit(Prestation $greenSpace, Request $request, EntityManagerInterface $manager)
    {
        $form = $this->createForm(PrestationType::class, $greenSpace);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $manager->persist($greenSpace);
            $manager->flush();

            $this->addFlash(
                'success',
                'Prestation successfully Updated'
            );

            return $this->redirectToRoute('admin_prestations_index');
        }

        return $this->render('admin/prestation/edit.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/admin/prestations/{id}/delete", name="admin_prestations_delete", methods={"DELETE"})
     */
    public function delete(Prestation $greenSpace, EntityManagerInterface $manager, Request $request): Response
    {
        if ($this->isCsrfTokenValid('delete' . $greenSpace->getId(), $request->get('_token'))) {
            $manager->remove($greenSpace);
            $manager->flush();
            $this->addFlash('success', 'Prestation successfully deleted');
        }

        $referer = $request->headers->get('referer');

        return $this->redirect($referer);
    }
}
