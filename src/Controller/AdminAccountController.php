<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class AdminAccountController extends AbstractController
{
    /**
     * Permet à l'admin de ce connecter
     * 
     * @Route("/admin/login", name="admin_account_login")
     */
    public function index(AuthenticationUtils $utils)
    {
        $error = $utils->getLastAuthenticationError();
        $username = $utils->getLastUsername();

        return $this->render('admin/account/login.html.twig', [
            'hasError' => $error !== null,
            'username' => $username
        ]);
    }


    /**
     * Permet à l'admin de ce deconnecter
     * 
     * @Route("/admin/logout", name="admin_account_logout")
     */
    public function logout()
    { }
}
