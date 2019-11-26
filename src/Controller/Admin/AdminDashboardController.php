<?php

namespace App\Controller\Admin;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Service\Stats;

class AdminDashboardController extends AbstractController
{
    /**
     * @Route("/admin", name="admin_dashboard")
     */
    public function index(Stats $stat)
    {

        return $this->render('admin/dashboard/index.html.twig', [
            'stats' => $stat->getStats(),
            'bestAds' => $stat->getAdsStats("DESC"),
            'worstAds' => $stat->getAdsStats("ASC")
        ]);
    }
}
