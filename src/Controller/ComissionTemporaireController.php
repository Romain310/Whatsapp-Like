<?php

// src/Controller/ComissionTemporaireController.php

namespace App\Controller;

use App\Entity\CommissionTemporaire;
use App\Entity\NotificationCommissionTemporaire;
use App\Entity\User;
use App\Form\CommissionTemporaireFormType;
use App\Repository\CommissionTemporaireRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;

class ComissionTemporaireController extends AbstractController
{
    #[Route('/creationCommissionTemporaire', name: 'creationCommissionTemporaire')]
    public function creationCommissionTemporaire(Request $request, EntityManagerInterface $entityManager): Response
    {
        $userRepository = $entityManager->getRepository(User::class);

        $user = $this->getUser();

        $commissionTemporaire = new CommissionTemporaire();
        $commissionTemporaire->setDebut(new \DateTimeImmutable('now'));
        $commissionTemporaire->setCloture(new \DateTimeImmutable('tomorrow'));
        $commissionTemporaire->setCreateur($user);

        $form = $this->createForm(CommissionTemporaireFormType::class, $commissionTemporaire);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $debut = $commissionTemporaire->getDebut();
            $cloture = $commissionTemporaire->getCloture();
            if ($cloture < $debut) {
                $this->addFlash('error', 'La date de clôture doit être postérieure à la date de début.');
            } else {
                $users = $userRepository->findAll();
                foreach ($users as $user) {
                    $notificationCommissionTemporaire = new NotificationCommissionTemporaire();
                    $notificationCommissionTemporaire->setUser($user);
                    $notificationCommissionTemporaire->setActive(true);
                    $notificationCommissionTemporaire->setCommissionTemporaire($commissionTemporaire);
                    $commissionTemporaire->addNotificationsUser($notificationCommissionTemporaire);
                }
                $entityManager->persist($commissionTemporaire);
                $entityManager->flush();
                return $this->redirectToRoute('home');
            }
        }
        return $this->render('commissionTemporaire.html.twig', [
            'commissionTemporaireForm' => $form,
        ]);
    }
}