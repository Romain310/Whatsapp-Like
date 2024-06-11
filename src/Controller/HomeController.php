<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    #[Route('/', name: 'home')]
    public function index(): Response
    {
        #Si l'utilisateur n'est pas connecté.
        return $this->render('connexion.html.twig');

        #Si l'utilisateur est connecté.
        #return $this->render('index.html.twig');
    }
}
