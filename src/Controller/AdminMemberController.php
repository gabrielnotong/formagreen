<?php
declare(strict_types=1);

namespace App\Controller;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminMemberController extends AbstractController
{
    /**
     * @Route("/admin/members/{id}/disable", name="admin_members_disable", methods={"PATCH"})
     */
    public function disable(User $user, EntityManagerInterface $manager, Request $request): Response
    {
        if ($this->isCsrfTokenValid('disable' . $user->getId(), $request->get('_token'))) {
            $user->setStatus(!$user->getStatus());

            $manager->flush();
            $this->addFlash('success', 'Action successfully performed');
        }

        $referer = $request->headers->get('referer');

        return $this->redirect($referer);
    }

    /**
     * @Route("/admin/members/{id}/delete", name="admin_members_delete", methods={"DELETE"})
     */
    public function delete(User $user, EntityManagerInterface $manager, Request $request): Response
    {
        if ($this->isCsrfTokenValid('delete' . $user->getId(), $request->get('_token'))) {
            $user->setDeleted(true);

            $manager->flush();
            $this->addFlash('success', 'Action successfully performed');
        }

        $referer = $request->headers->get('referer');

        return $this->redirect($referer);
    }

    /**
     * @Route("/admin/members/{id}/prestations", name="admin_members_prestations")
     */
    public function prestations(User $user): Response
    {
        return $this->render('admin/member/show_prestations.html.twig', [
            'prestations' =>  $user->getPrestations()
        ]);
    }
}
