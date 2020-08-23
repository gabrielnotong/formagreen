<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\Partner;
use App\Form\PartnerType;
use App\Repository\PartnerRepository;
use App\Service\Paginator;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminPartnerController extends AbstractController
{
    /**
     * @Route("/admin/partner/{page<\d+>?1}", name="admin_partners_index")
     */
    public function index(int $page, Paginator $paginator, PartnerRepository $repository)
    {
        $paginator->setCurrentPage($page)
            ->setQuery($repository->findAllAvailable());

        return $this->render('admin/partner/index.html.twig', [
            'paginator' => $paginator,
        ]);
    }

    /**
     * @Route("/admin/partner/create", name="admin_partners_create")
     */
    public function create(Request $request, EntityManagerInterface $manager)
    {
        $partner = new Partner();

        $form = $this->createForm(PartnerType::class, $partner);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $manager->persist($partner);
            $manager->flush();

            $this->addFlash(
                'success',
                'Partner successfully created'
            );

            return $this->redirectToRoute('admin_partners_index');
        }

        return $this->render('admin/partner/add.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/admin/partners/{id}/disable", name="admin_partners_disable", methods={"PATCH"})
     */
    public function disable(Partner $partner, EntityManagerInterface $manager, Request $request): Response
    {
        if ($this->isCsrfTokenValid('disable' . $partner->getId(), $request->get('_token'))) {
            $partner->setStatus(!$partner->getStatus());

            $manager->flush();
            $this->addFlash('success', 'Partner successfully disabled');
        }

        $referer = $request->headers->get('referer');

        return $this->redirect($referer);
    }

    /**
     * @Route("/admin/partners/{id}/delete", name="admin_partners_delete", methods={"DELETE"})
     */
    public function delete(Partner $partner, EntityManagerInterface $manager, Request $request): Response
    {
        if ($this->isCsrfTokenValid('delete' . $partner->getId(), $request->get('_token'))) {
            $partner->setDeleted(true);

            $manager->flush();
            $this->addFlash('success', 'Partner successfully deleted');
        }

        $referer = $request->headers->get('referer');

        return $this->redirect($referer);
    }
}
