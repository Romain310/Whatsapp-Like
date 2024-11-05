<?php

// src/Controller/PageNotFoundController.php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PageNotFoundController extends AbstractController
{
    #[Route('/PageNotFound', name: 'page_not_found')]
    public function pageNotFound(): Response
    {
        return $this->render('acces_interdit/error404.html.twig');
    }
}