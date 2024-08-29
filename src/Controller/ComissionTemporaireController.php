<?php

// src/Controller/AdminController.php

namespace App\Controller;

use App\Entity\CommissionTemporaire;
use App\Form\CommissionTemporaireFormType;
use App\Repository\CommissionTemporaireRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ComissionTemporaireController extends AbstractController
{
    #[Route('/creationCommissionTemporaire', name: 'creationCommissionTemporaire')]
    public function creationCommissionTemporaire(EntityManagerInterface $entityManager): Response
    {
        $commissionTemporaireRepository = $entityManager->getRepository(CommissionTemporaire::class);

        $commissionTemporaire = new CommissionTemporaire();
        $commissionTemporaire->setDebut(new \DateTimeImmutable('now'));
        $commissionTemporaire->setCloture(new \DateTimeImmutable('tomorrow'));

        $form = $this->createForm(CommissionTemporaireFormType::class, $commissionTemporaire);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($commissionTemporaire);
            $entityManager->flush();
        }
        return $this->render('commissionTemporaire.html.twig', [
            'commissionTemporaireForm' => $form,
        ]);
    }
}

