<?php

namespace App\Controller;

use App\Entity\Appointment;
use App\Form\AppointmentTypeFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/afspraak')]
#[IsGranted('ROLE_USER')] // Alleen voor ingelogde gebruikers met ROLE_USER
class AppointmentController extends AbstractController
{
    #[Route('', name: 'app_appointment_book')]
    public function book(Request $request, EntityManagerInterface $em): Response
    {
        $appointment = new Appointment();
        $form = $this->createForm(AppointmentTypeFormType::class, $appointment);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Start- en eindtijd worden direct uit het formulier gehaald
            $startDateTime = $appointment->getStartTime();

            // Bepaal duur op basis van type afspraak
            $duration = Appointment::APPOINTMENT_DURATIONS[$appointment->getAppointmentType()] ?? 60;
            $endDateTime = (clone $startDateTime)->modify("+{$duration} minutes");

            $appointment->setEndTime($endDateTime);

            // Check voor overlappende afspraken
            $existing = $em->getRepository(Appointment::class)->findOverlappingAppointments(
                $startDateTime,
                $endDateTime
            );

            if (count($existing) > 0) {
                $this->addFlash('error', 'Deze tijd is al gereserveerd. Kies een andere tijd.');
            } else {
                $appointment->setUser($this->getUser());
                $appointment->setStatus(Appointment::STATUS_PENDING);
                $appointment->setCreatedAt(new \DateTime());

                $em->persist($appointment);
                $em->flush();

                $this->addFlash('success', 'Afspraak is ingediend en wacht op goedkeuring.');
                return $this->redirectToRoute('app_appointment_book');
            }
        }

        return $this->render('appointment/book.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/schema', name: 'app_appointment_schedule')]
    public function schedule(EntityManagerInterface $em): Response
    {
        // Start van de week (maandag)
        $startOfWeek = (new \DateTime())->modify('monday this week')->setTime(0,0);
        // Einde van de week (zondag)
        $endOfWeek = (clone $startOfWeek)->modify('+6 days')->setTime(23,59,59);

        $appointments = $em->getRepository(Appointment::class)->createQueryBuilder('a')
            ->where('a.status = :status')
            ->andWhere('a.startTime BETWEEN :start AND :end')
            ->setParameters([
                'status' => Appointment::STATUS_APPROVED,
                'start' => $startOfWeek,
                'end' => $endOfWeek,
            ])
            ->orderBy('a.startTime', 'ASC')
            ->getQuery()
            ->getResult();

        return $this->render('appointment/schedule.html.twig', [
            'appointments' => $appointments,
            'startOfWeek' => $startOfWeek,
        ]);
    }
}
