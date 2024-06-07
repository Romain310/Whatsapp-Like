<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MembresController extends AbstractController
{
    #[Route('/membres', name: 'membres')]
    public function index(): Response
    {
        return $this->render('membres.html.twig');
    }

}
