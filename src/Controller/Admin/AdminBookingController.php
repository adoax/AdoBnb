<?php

namespace App\Controller\Admin;

use App\Entity\Booking;
use App\Form\AdminBookingType;
use App\Repository\BookingRepository;
use App\Service\Paginator;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

class AdminBookingController extends AbstractController
{
    /**
     * @Route("/admin/bookings/{page<\d+>?1}", name="admin_booking_index")
     */
    public function index(BookingRepository $bookings, $page, Paginator $paginator)
    {
        $paginator->setEntityClass(Booking::class)
        ->setCurrentPage($page)
        ->setLimit(20);

        return $this->render('admin/booking/index.html.twig', [
            'pagination' => $paginator,
        ]);
    }

    /**
     * Permet a l'admin de editer un resarvation
     *
     * @Route("/admin/bookings/{id}/edit", name="admin_booking_edit")
     * @return Response
     */
    public function edit(Request $request, Booking $bookings)
    {
        $form = $this->createForm(AdminBookingType::class, $bookings);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();

            $em->persist($bookings);
            $em->flush();

            $this->addFlash('success', "Vous avez bien modifer la reservation n°{$bookings->getId()}");

            return $this->redirectToRoute('admin_booking_index');
        }

        return $this->render("admin/booking/edit.html.twig", [
            'form' => $form->createView(),
            'booking' => $bookings
        ]);
    }

    /**
     * Permet à l'adin de suppromer une annonce
     *
     * @Route("/admin/bookings/{id}/delete", name="admin_booking_delete")
     * @return Response
     */
    public function delete(Booking $bookings)
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($bookings);
        $em->flush();

        $this->addFlash('success', "vous avez bien supprimer la reservation de {$bookings->getBooker()->getFullName()}");

        return $this->redirectToRoute('admin_booking_index');
    }
}
