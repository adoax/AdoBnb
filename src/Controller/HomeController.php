<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
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


    /**
     * @Route("/home/{prenom}", name="homepage")
     */
    public function home($prenom ="boby") {
        
        return $this->render('home.html.twig', [
            'prenom' => $prenom
        ]);
    }
}
