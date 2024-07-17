<?php

namespace App\Controller;

use App\Entity\Commission;
use App\Entity\Message;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    #[Route('/', name: 'home')]
    public function index(EntityManagerInterface $entityManager): Response
    {
        $messages = $entityManager->getRepository(Message::class)->findAll();

        $params = ["messages" => $messages];

        #Si l'utilisateur n'est pas connecté.
        return $this->render('index.html.twig', $params);
    }
//    #[Route('/comission/{idCommission}', name: 'messageCommission')]
//    public function messageCommission(EntityManagerInterface $entityManager, int $idCommission): Response
//    {
//        $commission = $entityManager->getRepository(Commission::class)->find($idCommission);
////        $messages = $entityManager->getRepository(Message::class)->findAll();
//
//        $params = ["messages" => $commission->getMessages()];
//
//        #Si l'utilisateur n'est pas connecté.
//        return $this->render('index.html.twig', $params);
//    }
}
