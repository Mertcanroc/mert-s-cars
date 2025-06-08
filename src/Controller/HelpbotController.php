<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HelpbotController extends AbstractController
{
    #[Route('/helpbot', name: 'app_helpbot')]
    public function index(): Response
    {
        return $this->render('helpbot/index.html.twig', [
            'controller_name' => 'HelpbotController',
        ]);
    }
}
