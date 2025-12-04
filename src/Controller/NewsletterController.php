<?php

namespace App\Controller;

use App\Entity\NewsletterSubscriber;
use App\Form\NewsletterSubscribeType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


class NewsletterController extends AbstractController
{
    #[Route('/newsletter/subscribe', name: 'newsletter_subscribe')]
    public function subscribe(
        Request $request,
        EntityManagerInterface $em
    ): Response {
        $subscriber = new NewsletterSubscriber();
        $subscriber->setCreatedAt(new \DateTimeImmutable()); // ✅ Added this

        $form = $this->createForm(NewsletterSubscribeType::class, $subscriber);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Check duplicate email
            $existing = $em->getRepository(NewsletterSubscriber::class)
                ->findOneBy(['email' => $subscriber->getEmail()]);

            if ($existing) {
                // Flash message translated
                $this->addFlash('info', 'You are already subscribed.');
            } else {
                $em->persist($subscriber);
                $em->flush();
                // Flash message translated
                $this->addFlash('success', 'You have successfully subscribed!');
            }

            return $this->redirectToRoute('app_home');
        }

        return $this->render('newsletter/form.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
