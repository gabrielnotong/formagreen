<?php
declare(strict_types=1);

namespace App\Controller;

use App\Entity\TrainingCenter;
use App\Repository\UserRepository;
use App\Service\Paginator;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminTrainingCenterController extends AbstractController
{
    /**
     * @Route("/admin/trainings/{page<\d+>?1}", name="admin_trainings_index")
     */
    public function index(int $page, UserRepository $repository, Paginator $paginator): Response
    {
        $paginator
            ->setCurrentPage($page)
            ->setQuery($repository->findAllTrainingCenters());

        return $this->render('admin/training/index.html.twig', ['paginator' => $paginator]);
    }
}
