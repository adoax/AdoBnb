<?php

namespace App\Controller;

use App\Entity\Ad;
use App\Form\AdType;
use App\Repository\AdRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

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
     */
    public function create(Request $request)
    {
        $ad = new Ad();
        $form = $this->createForm(AdType::class, $ad);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
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
     * @Route("/{slug}", name="ads_show")
     */
    public function show(Ad $ad)
    {
        return $this->render('ad/show.html.twig', [
            'ad' => $ad
        ]);
    }
}
