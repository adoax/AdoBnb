<?php

namespace App\Controller;

use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/user")
 */
class UserController extends AbstractController
{
    /**
     * @Route("/{slug}", name="user_profil")
     */
    public function index(User $users)
    {
        
        return $this->render('user/index.html.twig', [
            'user' => $users,
        ]);
    }
}
