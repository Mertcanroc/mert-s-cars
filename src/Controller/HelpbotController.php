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
                $reply = 'U kunt contact opnemen via <a href="/contact">onze contactpagina</a>.';
            } elseif (str_contains($userInput, 'modellen') || str_contains($userInput, 'autos')) {
                $reply = 'Bekijk al onze modellen via <a href="/car">de modellenpagina</a>.';
            } elseif (str_contains($userInput, 'afspraak')) {
                $reply = 'Maak een afspraak via ons afsprakenformulier (nog niet beschikbaar).';
            } else {
                $reply = 'Dank voor uw vraag. Wij nemen spoedig contact met u op of bezoek onze <a href="/faq">FAQ</a>.';
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
