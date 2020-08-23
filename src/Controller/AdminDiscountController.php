<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\Discount;
use App\Form\DiscountType;
use App\Repository\DiscountRepository;
use App\Service\Paginator;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminDiscountController extends AbstractController
{
    /**
     * @Route("/admin/discounts/{page<\d+>?1}", name="admin_discounts_index")
     */
    public function index(int $page, Paginator $paginator, DiscountRepository $repository)
    {
        $paginator->setCurrentPage($page)
            ->setQuery($repository->findAllQuery());

        return $this->render('admin/discount/index.html.twig', [
            'paginator' => $paginator,
        ]);
    }

    /**
     * @Route("/admin/discounts/create", name="admin_discounts_create", methods={"POST", "GET"})
     */
    public function create(Request $request, EntityManagerInterface $manager)
    {
        $discount = new Discount();

        $form = $this->createForm(DiscountType::class, $discount);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $manager->persist($discount);
            $manager->flush();

            $this->addFlash(
                'success',
                'Discount successfully created'
            );

            return $this->redirectToRoute('admin_discounts_index');
        }

        return $this->render('admin/discount/add.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/admin/discounts/{id}/edit", name="admin_discounts_edit", methods={"POST", "GET"})
     */
    public function edit(Discount $discount, Request $request, EntityManagerInterface $manager)
    {
        $form = $this->createForm(DiscountType::class, $discount);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $manager->persist($discount);
            $manager->flush();

            $this->addFlash(
                'success',
                'Discount successfully Updated'
            );

            return $this->redirectToRoute('admin_discounts_index');
        }

        return $this->render('admin/discount/edit.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/admin/discounts/{id}/delete", name="admin_discounts_delete", methods={"DELETE"})
     */
    public function delete(Discount $discount, EntityManagerInterface $manager, Request $request): Response
    {
        if ($this->isCsrfTokenValid('delete' . $discount->getId(), $request->get('_token'))) {
            $manager->remove($discount);
            $manager->flush();
            $this->addFlash('success', 'Discount successfully deleted');
        }

        $referer = $request->headers->get('referer');

        return $this->redirect($referer);
    }
}
