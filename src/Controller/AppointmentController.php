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

#[Route('/afspraak')]
#[IsGranted('ROLE_USER')]
class AppointmentController extends AbstractController
{
    #[Route('', name: 'app_appointment_book')]
    public function book(Request $request, EntityManagerInterface $em): Response
    {
        $appointment = new Appointment();
        $appointment->setDate(new \DateTime()); // vervul met vandaag (default)
        $form = $this->createForm(AppointmentTypeFormType::class, $appointment);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $appointment = $form->getData();

            // Start- en eindtijd worden direct uit het formulier gehaald
            $startDateTime = $appointment->getStartTime();

            if (!$appointment->getDate()) {
                $appointment->setDate(new \DateTime()); // Fallback if user left it empty
            }

            // Bepaal duur op basis van type afspraak
            $duration = Appointment::APPOINTMENT_DURATIONS[$appointment->getAppointmentType()] ?? 60;
            $endDateTime = (clone $startDateTime)->modify("+{$duration} minutes");
            // $combinedStartDate = date('Y-m-d H:i:s', strtotime("$appointment->getDate() $appointment->getStartTime()"));
            $combinedStartDate = new DateTime($appointment->getDate()->format('Y-m-d') .' ' .$appointment->getStartTime()->format('H:i:s'));
            $combinedEndDate = new DateTime($appointment->getDate()->format('Y-m-d') .' ' .$endDateTime->format('H:i:s'));
            $appointment->setStartTime($combinedStartDate);
            $appointment->setEndTime($combinedEndDate);

            // Check voor overlappende afspraken
            $existing = $em->getRepository(Appointment::class)->findOverlappingAppointments(
                $startDateTime,
                $endDateTime
            );
// flash messages voor errors en successes
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
        $startOfWeek = (new \DateTime())->modify('monday this week')->setTime(0,0); //object van datetime en word naar dit week gezet door mondaythisweek
        // Einde van de week (zondag)
        $endOfWeek = (clone $startOfWeek)->modify('+6 days')->setTime(23,59,59);

        $appointments = $em->getRepository(Appointment::class)->createQueryBuilder('a') //allias voor appointment
            ->where('a.status = :status') // filtert op alleen afspraken met een bepaalde status.
            ->andWhere('a.startTime BETWEEN :start AND :end') //filtert op afspraken die binnen deze week vallen.
            ->setParameters([ //vult de waardes in
                'status' => Appointment::STATUS_APPROVED,
                'start' => $startOfWeek,
                'end' => $endOfWeek,
            ])
            ->orderBy('a.startTime', 'ASC')
            ->getQuery()
            ->getResult(); //voert query uit en geeft een lijst van Appointment objecten terug.

        return $this->render('appointment/schedule.html.twig', [
            'appointments' => $appointments,
            'startOfWeek' => $startOfWeek,
        ]);
    }
}
