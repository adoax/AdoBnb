<?php

namespace App\Controller;


use App\Repository\AdRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/")
 */
class HomeController extends AbstractController
{
    /**
     * @Route("", name="indexpage")
     */
    public function index(AdRepository $ads, UserRepository $uses)
    {

        return $this->render('index.html.twig', [
            'ads' => $ads->findBestAds(3),
            'users' => $uses->findBestUser()
        ]);
    }
}
