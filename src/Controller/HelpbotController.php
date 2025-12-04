<?php

namespace App\Controller;

use App\Entity\Message;
use App\Form\MessageType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HelpbotController extends AbstractController
{
    #[Route('/helpbot', name: 'app_helpbot')]
    public function index(Request $request, EntityManagerInterface $em): Response
    {

        $message = new Message();
        $message->setTimestamp(new \DateTimeImmutable());

        if ($this->getUser()) {
            $message->setUser($this->getUser());
        }

        $form = $this->createForm(MessageType::class, $message);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $userInput = strtolower($message->getMessage());

            // Simple auto-reply logic
            if (str_contains($userInput, 'contact')) {
                $reply = 'You can contact us via <a href="/contact">our contact page</a>.';
            } elseif (str_contains($userInput, 'modellen') || str_contains($userInput, 'autos')) {
                $reply = 'Check out all our models on the <a href="/car">models page</a>.';
            } elseif (str_contains($userInput, 'afspraak')) {
                $reply = 'Make an appointment via our appointment form (not yet available).';
            } else {
                $reply = 'Thanks for your question. We will contact you shortly or visit our <a href="/faq">FAQ</a>.';
            }

            $message->setBotResponse($reply);

            $em->persist($message);
            $em->flush();

            return $this->redirectToRoute('app_helpbot');
        }

        $messages = $em->getRepository(Message::class)->findBy([], ['timestamp' => 'ASC']);

        return $this->render('helpbot/index.html.twig', [
            'form' => $form->createView(),
            'messages' => $messages,
        ]);
    }

    #[Route('/helpbot/clear', name: 'helpbot_clear', methods: ['POST'])]
    public function clearMessages(EntityManagerInterface $em): Response
    {
        $messages = $em->getRepository(Message::class)->findAll();
        foreach ($messages as $msg) {
            $em->remove($msg);
        }
        $em->flush();

        return new Response('Chat cleared', 200);
    }
}
