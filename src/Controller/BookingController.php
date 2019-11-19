<?php

namespace App\Controller;

use App\Entity\Booking;
use App\Entity\Ad;
use App\Form\BookingType;
use DateTime;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;


class BookingController extends AbstractController
{

    /**
     * @Route("/ads/{slug}/book", name="booking_route")
     * @IsGranted("ROLE_USER")
     */
    public function book(Ad $ad, Request $request): Response
    {
        $booking =  new Booking();

        $form = $this->createForm(BookingType::class, $booking);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $em = $this->getDoctrine()->getManager();

            $booking->setBooker($this->getUser())
                ->setAd($ad);
            //CréationDateReservation + CalculMontant : voir Entity booking->prePersist();

            //Verifie si les dates ne sont pas disponible
            if (!$booking->isBookableDate()) {
                $this->addFlash('warning', "Les dates choisi ne peuvent être réserves : elles sont deja occuper");
            } else {
                $em->persist($booking);
                $em->flush();

                return $this->redirectToRoute("booking_show", ['id' => $booking->getId(), 'withAlert' => true]);
            }
        }


        return $this->render('booking/book.html.twig', [
            'ad' => $ad,
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/booking/{id}", name="booking_show")
     * @Security("is_granted('ROLE_USER') and user === booking.getBooker()", message="Cette reservation ne vous appartient pas !")
     * 
     */
    public function show(Booking $booking)
    {
        return $this->render("booking/show.html.twig", [
            'booking' => $booking
        ]);
    }
}
