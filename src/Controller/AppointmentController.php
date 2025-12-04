<?php

namespace App\Controller;

use App\Entity\Appointment;
use App\Form\AppointmentTypeFormType;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/appointment')]  // changed from /afspraak to English
#[IsGranted('ROLE_USER')]
class AppointmentController extends AbstractController
{
    #[Route('', name: 'app_appointment_book')]
    public function book(Request $request, EntityManagerInterface $em): Response
    {
        $appointment = new Appointment();
        $appointment->setDate(new \DateTime()); // fill it with today (default)
        $form = $this->createForm(AppointmentTypeFormType::class, $appointment);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $appointment = $form->getData();

            $startDateTime = $appointment->getStartTime();

            if (!$appointment->getDate()) {
                $appointment->setDate(new \DateTime()); // Fallback if empty
            }

            $duration = Appointment::APPOINTMENT_DURATIONS[$appointment->getAppointmentType()] ?? 60;
            $endDateTime = (clone $startDateTime)->modify("+{$duration} minutes");

            $combinedStartDate = new DateTime($appointment->getDate()->format('Y-m-d') .' ' .$appointment->getStartTime()->format('H:i:s'));
            $combinedEndDate = new DateTime($appointment->getDate()->format('Y-m-d') .' ' .$endDateTime->format('H:i:s'));

            $appointment->setStartTime($combinedStartDate);
            $appointment->setEndTime($combinedEndDate);

            $existing = $em->getRepository(Appointment::class)->findOverlappingAppointments(
                $startDateTime,
                $endDateTime
            );

            // **FLASH MESSAGES UPDATED TO ENGLISH**
            if (count($existing) > 0) {
                $this->addFlash('error', 'This time slot is already reserved. Please choose another.');
            } else {
                $appointment->setUser($this->getUser());
                $appointment->setStatus(Appointment::STATUS_PENDING);
                $appointment->setCreatedAt(new \DateTime());

                $em->persist($appointment);
                $em->flush();

                $this->addFlash('success', 'Your appointment request has been submitted and is pending approval.');
                return $this->redirectToRoute('app_appointment_book');
            }
        }

        return $this->render('appointment/book.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/schedule', name: 'app_appointment_schedule')]
    public function schedule(EntityManagerInterface $em): Response
    {
        $startOfWeek = (new \DateTime())->modify('monday this week')->setTime(0,0);
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
