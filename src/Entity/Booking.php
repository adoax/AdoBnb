<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\BookingRepository")
 * @ORM\HasLifecycleCallbacks
 */
class Booking
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="bookings")
     * @ORM\JoinColumn(nullable=false)
     */
    private $booker;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Ad", inversedBy="bookings")
     * @ORM\JoinColumn(nullable=false)
     */
    private $ad;

    /**
     * @ORM\Column(type="datetime")
     * @Assert\Date(message="Attention, la date d'arrivée doit être au bon format")
     * @Assert\GreaterThan("today", message="La date d'arrivée doit être ultérieure à la date d'aujourd'hui !")
     */
    private $startDate;

    /**
     * @ORM\Column(type="datetime")
     * @Assert\Date(message="Attention, la date de fin doit être au bon format")
     * @Assert\GreaterThan(propertyPath="StartDate", message="La date de depart doit être plus eloigner que celle d'arrivé !")
     */
    private $endDate;

    /**
     * @ORM\Column(type="datetime")
     */
    private $createdAt;

    /**
     * @ORM\Column(type="float")
     */
    private $amout;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $comment;

    /**
     * Calcul de la duré
     *
     * @ORM\PrePersist
     * @ORM\PreUpdate
     */
    public function prePersist()
    {
        if (empty($this->createdAt)) {
            $this->createdAt = new \DateTime();
        }

        if (empty($this->amout)) {
            $this->amout = $this->ad->getPrice() * $this->getDuration();
        }
    }

    public function getDuration()
    {
        $diff = $this->endDate->diff($this->startDate);
        return $diff->days;
    }

    /**
     * Permet de ne pas avoir é reservation sur la même date
     *
     * @return boolean
     */
    public function isBookableDate()
    {
        //REtrouve les dates qui sont imposible pour l'annonce
        $notAvailableDays = $this->ad->getNotAvailableDays();
        //compare les dates choisies avec les dates imposibles
        $bookingDay = $this->getDays();

        $formatDay = function ($day) {
            return $day->format('Y-m-d');
        };

        //Tableau des chaines de caractères de mes journées
        $days = array_map($formatDay, $bookingDay);

        $notAvailable = array_map($formatDay, $notAvailableDays);

        foreach ($days as $day) {
            if (array_search($day, $notAvailable) !== false) return false;
        }
        return true;
    }

    /**
     * Permet de recuperer un tableau des journées qui correspond à ma reservation
     * 
     * @return array un Tableau d'objet DateTime représenant les jours de la réservation
     */
    public function getDays()
    {
        $result = range(
            $this->getStartDate()->getTimestamp(),
            $this->getEndDate()->getTimestamp(),
            24 * 60 * 60
        );
        $days = array_map(function ($dayTimestamp) {
            return new \DateTime(date('Y-m-d', $dayTimestamp));
        }, $result);

        return $days;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getBooker(): ?User
    {
        return $this->booker;
    }

    public function setBooker(?User $booker): self
    {
        $this->booker = $booker;

        return $this;
    }

    public function getAd(): ?Ad
    {
        return $this->ad;
    }

    public function setAd(?Ad $ad): self
    {
        $this->ad = $ad;

        return $this;
    }

    public function getStartDate(): ?\DateTimeInterface
    {
        return $this->startDate;
    }

    public function setStartDate(\DateTimeInterface $startDate): self
    {
        $this->startDate = $startDate;

        return $this;
    }

    public function getEndDate(): ?\DateTimeInterface
    {
        return $this->endDate;
    }

    public function setEndDate(\DateTimeInterface $endDate): self
    {
        $this->endDate = $endDate;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getAmout(): ?float
    {
        return $this->amout;
    }

    public function setAmout(float $amout): self
    {
        $this->amout = $amout;

        return $this;
    }

    public function getComment(): ?string
    {
        return $this->comment;
    }

    public function setComment(?string $comment): self
    {
        $this->comment = $comment;

        return $this;
    }
}
