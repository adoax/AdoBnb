<?php

namespace App\Controller\Admin;

use App\Entity\Ad;
use App\Form\AdType;
use App\Service\Paginator;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

class AdminAdController extends AbstractController
{
    /**
     * @Route("/admin/ads/{page<\d+>?1}", name="admin_ads_index")
     */
    public function index($page, Paginator $paginator)
    {

        $paginator->setEntityClass(Ad::class)
            ->setCurrentPage($page);

        return $this->render('admin/ad/index.html.twig', [
            'pagination' => $paginator
        ]);
    }

    /**
     * Permet de editer une annonce, en tant que Admin
     *
     * @Route("/admin/ads/{slug}/edit", name="admin_ads_edit")
     * @return Response
     */
    public function edit(Request $request, Ad $ad)
    {
        $form = $this->createForm(AdType::class, $ad);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($ad);
            $em->flush();

            $this->addFlash('success', "L'article est bien été modifer");

            return $this->redirectToRoute('admin_ads_index');
        }

        return $this->render('admin/ad/edit.html.twig', [
            'form' => $form->createView(),
            'ad' => $ad
        ]);
    }

    /**
     * Permet à l'adin de suppromer une annonce
     *
     * @Route("/admin/ads/{id}/delete", name="admin_ads_delete")
     * @return Response
     */
    public function delete(Ad $ad)
    {
        if (count($ad->getBookings()) > 0) {
            $this->addFlash('warning', "Vous ne pouvez pas supprimer l'annonce <strong> {$ad->getTitle()} </strong> car elle possede des réservations");
        } else {
            $em = $this->getDoctrine()->getManager();
            $em->remove($ad);
            $em->flush();

            $this->addFlash('success', "vous avez bien supprimer l'annonce");
        }
        return $this->redirectToRoute('admin_ads_index');
    }
}
