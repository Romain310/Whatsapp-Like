<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class AccesInterditController extends AbstractController
{
    #[Route('/accesinterdit', name: 'app_accesinterdit')]
    public function index(): Response
    {
        return $this->render('acces_interdit/index.html.twig', [
            'controller_name' => 'AccesInterditController',
        ]);
    }
}