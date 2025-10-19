<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;


#[Route('/command', name: 'app_commande')]
final class CommandeController extends AbstractController
{
    #[Route('/list', name: 'commande_index')]
    public function index(): Response
    {
        return $this->render('commande/index.html.twig', [
            "class" => "3A16",
        ]);
    }

   #[Route('/go', name: 'commande_goToIndex')]
    public function goToIndex()
    {

        return $this->redirectToRoute('manual_hello');
    }
}