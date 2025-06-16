<?php
namespace App\Controller;

use App\Entity\Appointment;
use App\Repository\TestDriveRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/beheer/afspraken')]
#[IsGranted('ROLE_BEHEERDER')]
class BeheerController extends AbstractController
{
    #[Route('', name: 'beheer_appointments_list')]
    public function list(EntityManagerInterface $em): Response
    {
        $pendingAppointments = $em->getRepository(Appointment::class)
            ->findBy(['status' => 'pending']);

        return $this->render('beheer/appointments_list.html.twig', [
            'appointments' => $pendingAppointments,
        ]);
    }

    #[Route('/{id}/approve', name: 'appointment_approve')]
    public function approve(Appointment $appointment, EntityManagerInterface $em)
    {
        $appointment->setStatus('approved');
        $em->flush();

        $this->addFlash('success', 'Afspraak goedgekeurd.');
        return $this->redirectToRoute('beheer_appointments_list');
    }

    #[Route('/{id}/reject', name: 'appointment_reject')]
    public function reject(Appointment $appointment, EntityManagerInterface $em)
    {
        $em->remove($appointment);
        $em->flush();

        $this->addFlash('success', 'Afspraak afgewezen en verwijderd.');
        return $this->redirectToRoute('beheer_appointments_list');
    }
    #[Route('/proefritten', name: 'beheer_test_drive_list')]
    public function testDriveList(TestDriveRepository $repository): Response
    {
        $this->denyAccessUnlessGranted('ROLE_BEHEERDER');

        $testDrives = $repository->findAll();

        return $this->render('beheer/test_drive_list.html.twig', [
            'testDrives' => $testDrives,
        ]);
    }

}
