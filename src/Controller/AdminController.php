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

#[Route('/admin/appointments')]
#[IsGranted('ROLE_ADMIN')]
class AdminController extends AbstractController
{
    #[Route('', name: 'admin_appointments_list')]
    public function list(EntityManagerInterface $em): Response
    {
        $pendingAppointments = $em->getRepository(Appointment::class)
            ->findBy(['status' => 'pending']);

        return $this->render('admin/appointments_list.html.twig', [
            'appointments' => $pendingAppointments,
        ]);
    }

    #[Route('/{id}/approve', name: 'admin_appointment_approve')]
    public function approve(Appointment $appointment, EntityManagerInterface $em)
    {
        $appointment->setStatus('approved');
        $em->flush();

        $this->addFlash('success', 'Appointment approved.');
        return $this->redirectToRoute('admin_appointments_list');
    }

    #[Route('/{id}/reject', name: 'admin_appointment_reject')]
    public function reject(Appointment $appointment, EntityManagerInterface $em)
    {
        $em->remove($appointment);
        $em->flush();

        $this->addFlash('success', 'Appointment rejected and deleted.');
        return $this->redirectToRoute('admin_appointments_list');
    }

    #[Route('/test-drives', name: 'admin_test_drive_list')]
    public function testDriveList(TestDriveRepository $repository): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        $testDrives = $repository->findAll();

        return $this->render('admin/test_drive_list.html.twig', [
            'testDrives' => $testDrives,
        ]);
    }

    #[Route('/users', name: 'admin_user_list')]
    public function userList(UserRepository $userRepository): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        return $this->render('admin/user_list.html.twig', [
            'users' => $userRepository->findAll(),
        ]);
    }

    #[Route('/users/{id}/change-role', name: 'admin_user_change_role', methods: ['POST'])]
    public function changeUserRole(Request $request, User $user, EntityManagerInterface $em): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        $newRole = $request->request->get('role');

        $validRoles = ['ROLE_ADMIN', 'ROLE_EMPLOYEE', 'ROLE_CUSTOMER'];

        if (!in_array($newRole, $validRoles)) {
            $this->addFlash('danger', 'Invalid role selected.');
        } else {
            $user->setRoles([$newRole]);
            $em->flush();
            $this->addFlash('success', 'User role updated.');
        }

        return $this->redirectToRoute('admin_user_list');
    }

    #[Route('/users/{id}/delete', name: 'admin_user_delete', methods: ['POST'])]
    public function deleteUser(User $user, EntityManagerInterface $em, Request $request): \Symfony\Component\HttpFoundation\RedirectResponse
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        // prevent deleting your own account
        if ($user === $this->getUser()) {
            $this->addFlash('danger', 'You cannot delete your own account.');
            return $this->redirectToRoute('admin_user_list');
        }

        // CSRF protection
        if ($this->isCsrfTokenValid('delete-user-' . $user->getId(), $request->request->get('_token'))) {
            $em->remove($user);
            $em->flush();

            $this->addFlash('success', 'User deleted successfully.');
        } else {
            $this->addFlash('danger', 'Invalid CSRF token.');
        }

        return $this->redirectToRoute('admin_user_list');
    }
}
