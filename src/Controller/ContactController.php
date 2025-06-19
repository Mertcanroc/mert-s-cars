<?php

namespace App\Controller;

use App\Entity\ContactRequest;
use App\Form\ContactRequestType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ContactController extends AbstractController
{
    #[Route('/contact', name: 'app_contact')]
    public function index(Request $request, EntityManagerInterface $em): Response
    {
        $contactRequest = new ContactRequest();

        // Set createdAt and default status
        $contactRequest->setCreatedAt(new \DateTimeImmutable());
        $contactRequest->setStatus('nieuw');

        // Set logged in user if available
        if ($this->getUser()) {
            $contactRequest->setUser($this->getUser());
        }

        $form = $this->createForm(ContactRequestType::class, $contactRequest);
        $form->handleRequest($request);

       if ($form->isSubmitted()) {
           if (!$this->getUser()) {
               $this->addFlash('danger', 'U moet ingelogd zijn of een account aanmaken om een contactverzoek te sturen.');
               return $this->redirectToRoute('app_contact');

           } elseif ($form->isValid()) {
               $contactRequest->setUser($this->getUser());
               $em->persist($contactRequest);
               $em->flush();

               $this->addFlash('success', 'Bedankt voor uw bericht. We nemen spoedig contact met u op.');
               return $this->redirectToRoute('app_contact');
           }

       }


        return $this->render('contact/index.html.twig', [
            'form' => $form->createView(),
        ]);
    }
    #[Route('/contact/requests', name: 'app_contact_requests')]
    public function list(EntityManagerInterface $em): Response
    {
        $this->denyAccessUnlessGranted('ROLE_MEDEWERKER');

        $requests = $em->getRepository(ContactRequest::class)->findBy([], ['createdAt' => 'DESC']);

        return $this->render('contact/requests.html.twig', [
            'requests' => $requests,
        ]);
    }
    #[Route('/contact/delete/{id}', name: 'contact_request_delete', methods: ['POST'])]
    public function delete(Request $request, ContactRequest $contactRequest, EntityManagerInterface $em): Response
    {
        if ($this->isCsrfTokenValid('delete-request-' . $contactRequest->getId(), $request->request->get('_token'))) {
            $em->remove($contactRequest);
            $em->flush();

            $this->addFlash('success', 'Contactverzoek verwijderd.');
        }

        return $this->redirectToRoute('app_contact_requests');
    }
}
