<?php

namespace App\Controller\Admin;
namespace App\Controller;

use App\Entity\NewsletterSubscriber;
use App\Form\NewsletterSendType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/beheer/newsletter')]
class NewsletterAdminController extends AbstractController
{
    #[Route('/send', name: 'beheer_newsletter_send')]
    public function send(
        Request $request,
        EntityManagerInterface $em,
        MailerInterface $mailer
    ): Response {
        $this->denyAccessUnlessGranted('ROLE_BEHEERDER');

        $form = $this->createForm(NewsletterSendType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            $subscribers = $em->getRepository(NewsletterSubscriber::class)->findAll();

            foreach ($subscribers as $subscriber) {
                $email = (new Email())
                    ->from('no-reply@jouwdomein.nl')
                    ->to($subscriber->getEmail())
                    ->subject($data['subject'])
                    ->html($data['content']);

                $mailer->send($email);
            }

            $this->addFlash('success', 'Nieuwsbrief verzonden!');
            return $this->redirectToRoute('beheer_newsletter_send');
        }

        return $this->render('beheer/newsletter/send.html.twig', [
            'form' => $form->createView(),
        ]);
    }


}


//hoe dit werkt: Maak een .env.local aan. Ga vervolgens naar je google gmail account, zorg dat 2FA aan staat
//vervolgens maak je een app wachtwoord aan. Kopieer en plak die code in de .env.local na de : van je mail dus zo:
// MAILER_DSN=smtp://jemail@gmail.com:agkxbgbnnvutcpwq@smtp.gmail.com:587?encryption=tls&auth_mode=login
// vergeet niet je mail te laten subscriben, hierna kan je naar http://127.0.0.1:8000/beheer/newsletter/send en kan je
// vervolgens een mail sturen om te testen. Na het versturen moet je via de terminal:
// php bin/console messenger:consume async -vv om worker te draaien. Zodra de worker draait, zal hij de mails uit de database halen en versturen.