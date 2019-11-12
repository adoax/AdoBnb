<?php

namespace App\Controller;

use App\Entity\Ad;
use App\Form\AdType;
use App\Entity\Image;
use App\Repository\AdRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @Route("/ads")
 */
class AdController extends AbstractController
{
    /**
     * @Route("", name="ads_index")
     */
    public function index(AdRepository $ads)
    {
        return $this->render('ad/index.html.twig', [
            'ads' => $ads->findBy([], ['id' => 'DESC']),
        ]);
    }

    /**
     * @Route("/new", name="ads_new")
     * @IsGranted("ROLE_USER", message="vous devez avoir un compte pour pouvoir créer une annonce !", statusCode=404)
     */
    public function create(Request $request)
    {
        $ad = new Ad();
        $form = $this->createForm(AdType::class, $ad);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $ad->setAuthor($this->getUser());
            $em->persist($ad);
            $em->flush();

            $this->addFlash('success', "L'article à bien étais créer");

            return $this->redirectToRoute('ads_index');
        }

        return $this->render('ad/new.html.twig', [
            'form' => $form->createView()
        ]);
    }
    /**
     * @Route("/{slug}/edit", name="ads_edit")
     * @Security("is_granted('ROLE_USER') and user === ad.getAuthor()", message="Cette annonce ne vous appartient pas, vous ne pouvez pas la modifier !")
     */
    public function edit(Request $request, Ad $ad ) {
        $form = $this->createForm(AdType::class, $ad);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($ad);
            $em->flush();

            $this->addFlash('success', "L'article à bien étais modifier");

            return $this->redirectToRoute("ads_index");
        }

        return $this->render('ad/edit.html.twig', [
            'ad' => $ad,
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/{slug}", name="ads_show")
     */
    public function show(Ad $ad)
    {
        return $this->render('ad/show.html.twig', [
            'ad' => $ad
        ]);
    }

    /**
     *
     * @Route("/ads/{slug}/delete", name="ads_delete")
     * 
     * @Security("is_granted('ROLE_USER') and user === ad.getAuthor()", message="vous ne pouvez pas supprimer un article qui n'est pas a vous !")
     */
    public function delete(Ad $ad){
        $em = $this->getDoctrine()->getManager();
        $em->remove($ad);
        $em->flush();
        $this->addFlash('success', "l'annonce <strong>{$ad->getTitle()}</strong> a bien été supprimé");
        return $this->redirectToRoute('ads_index');
    }
}
