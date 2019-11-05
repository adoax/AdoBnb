<?php

namespace App\Controller;


use App\Repository\AdRepository;
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
    public function index()
    {
        $lsg = [
            "tobby", "bybo", "yves", "magalie","naps"
        ];

        return $this->render('index.html.twig', [
            't' => $lsg
        ]);
    }

}
