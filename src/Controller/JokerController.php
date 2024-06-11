<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class JokerController extends AbstractController
{
    #[Route('/{joker}', name: 'joker')]
    public function joker($joker): Response
    {
        return $this->render('joker.html.twig');
    }
}