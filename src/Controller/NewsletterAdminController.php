<?php

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

#[Route('/admin/newsletter')]
class NewsletterAdminController extends AbstractController
{
    #[Route('/send', name: 'admin_newsletter_send')]
    public function send(
        Request $request,
        EntityManagerInterface $em,
        MailerInterface $mailer
    ): Response {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        $form = $this->createForm(NewsletterSendType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            $subscribers = $em->getRepository(NewsletterSubscriber::class)->findAll();

            foreach ($subscribers as $subscriber) {
                $email = (new Email())
                    ->from('no-reply@yourdomain.com')
                    ->to($subscriber->getEmail())
                    ->subject($data['subject'])
                    ->html($data['content']);

                $mailer->send($email);
            }

            // Flash message translated
            $this->addFlash('success', 'Newsletter sent!');
            return $this->redirectToRoute('admin_newsletter_send');
        }

        return $this->render('admin/newsletter/send.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}

// How this works: Create a .env.local file. Then go to your Google Gmail account and enable 2FA.
// Next, create an app password and copy it into .env.local after MAILER_DSN, for example:
// MAILER_DSN=smtp://youremail@gmail.com:yourapppassword@smtp.gmail.com:587?encryption=tls&auth_mode=login
// Make sure your email is subscribed. Then visit http://127.0.0.1:8000/admin/newsletter/send
// to send a test email. After sending, run in the terminal:
// php bin/console messenger:consume async -vv to start the WORKER.
// Once the worker is running, it will fetch and send emails from the database.
