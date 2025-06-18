<?php
namespace App\Controller;

use App\Entity\Appointment;
use App\Entity\User;
use App\Repository\TestDriveRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
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
    #[Route('/gebruikers', name: 'beheer_user_list')]
    public function userList(UserRepository $userRepository): Response
    {
        $this->denyAccessUnlessGranted('ROLE_BEHEERDER');

        return $this->render('beheer/user_list.html.twig', [
            'users' => $userRepository->findAll(),
        ]);
    }

    #[Route('/gebruikers/{id}/change-role', name: 'beheer_user_change_role', methods: ['POST'])]
    public function changeUserRole(Request $request, User $user, EntityManagerInterface $em): Response
    {
        $this->denyAccessUnlessGranted('ROLE_BEHEERDER');

        $newRole = $request->request->get('role');

        $validRoles = ['ROLE_BEHEERDER', 'ROLE_MEDEWERKER', 'ROLE_KLANT'];
        if (!in_array($newRole, $validRoles)) {
            $this->addFlash('danger', 'Ongeldige rol geselecteerd.');
        } else {
            $user->setRoles([$newRole]);
            $em->flush();
            $this->addFlash('success', 'Rol aangepast.');
        }

        return $this->redirectToRoute('beheer_user_list');
    }
    #[Route('/gebruikers/{id}/verwijder', name: 'beheer_user_delete', methods: ['POST'])]
    public function deleteUser(User $user, EntityManagerInterface $em, Request $request): \Symfony\Component\HttpFoundation\RedirectResponse
    {
        $this->denyAccessUnlessGranted('ROLE_BEHEERDER');

        // voorkomen dat je jezelf verwijdert
        if ($user === $this->getUser()) {
            $this->addFlash('danger', 'Je kunt je eigen account niet verwijderen.');
            return $this->redirectToRoute('beheer_user_list');
        }

        // CSRF bescherming
        if ($this->isCsrfTokenValid('delete-user-'.$user->getId(), $request->request->get('_token'))) {
            $em->remove($user);
            $em->flush();

            $this->addFlash('success', 'Gebruiker succesvol verwijderd.');
        } else {
            $this->addFlash('danger', 'Ongeldige CSRF token.');
        }

        return $this->redirectToRoute('beheer_user_list');
    }
}
