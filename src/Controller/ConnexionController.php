<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ConnexionController extends AbstractController
{
    #[Route(name: 'connexion')]
    public function login(Request $request): Response
    {
        if ($request->isMethod('POST')) {
            $username = $request->request->get('username');
            $password = $request->request->get('password');
            $email = $request->request->get('email');

            // Traitez les données de connexion
            // ...

            // Redirigez ou affichez un message
            // return $this->redirectToRoute('some_route');
        }
        return $this->render('connexion.html.twig');
    }
}
