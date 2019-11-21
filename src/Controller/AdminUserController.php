<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\AdminAccountType;
use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AdminUserController extends AbstractController
{
    /**
     * @Route("/admin/users", name="admin_user_index")
     */
    public function index(UserRepository $user)
    {
        return $this->render('admin/user/index.html.twig', [
            'users' => $user->findAll(),
        ]);
    }

    /**
     * Permet de l'admin de modifier un profil
     *
     * @Route("/admin/users/{id}/edit", name="admin_user_edit")
     * 
     * @param Request $request
     * @param User $user
     * @return Response
     */
    public function edit(Request $request, User $user)
    {
        $form = $this->createForm(AdminAccountType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();

            $this->addFlash('success', "Vous avez bien editer le profil de {$user->getfullName()}");

            return $this->redirectToRoute('admin_user_index');
        }

        return $this->render('admin/user/edit.html.twig', [
            'form' => $form->createView(),
            'user' => $user
        ]);
    }

    /**
     * Permet de l'admin de supprimer un utilisateur
     *
     * @Route("/admin/users{id}/delete", name="admin_user_delete")
     * 
     * @param User $user
     * @return Response
     */
    public function delete(User $user)
    {
        if (count($user->getBookings()) > 0) {
            $this->addFlash('warning', "Vous ne pouvez pas supprimer l'utilisateur <strong> {$user->getFullName()} </strong> car elle possede des Annonces / Reservation");
        } else {
            $em = $this->getDoctrine()->getManager();
            $em->remove($user);
            $em->flush();

            $this->addFlash('success', "Vous avez bien supprimer l'utilisateur");
        }

        return $this->redirectToRoute('admin_user_index');
    }
}
