<?php
declare(strict_types=1);

namespace App\Controller;

use App\Repository\UserRepository;
use App\Service\Paginator;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminUserLambdaController extends AbstractController
{
    /**
     * @Route("/admin/users/{page<\d+>?1}", name="admin_users_index")
     */
    public function index(int $page, UserRepository $repository, Paginator $paginator): Response
    {
        $paginator
            ->setCurrentPage($page)
            ->setQuery($repository->findAllUsersLambda());

        return $this->render('admin/userlambda/index.html.twig', ['paginator' => $paginator]);
    }
}
