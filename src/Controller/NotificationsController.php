<?php

// src/Controller/AdminController.php

namespace App\Controller;

use App\Entity\Commission;
use App\Entity\CommissionTemporaire;
use App\Entity\NotificationCommission;
use App\Entity\NotificationCommissionTemporaire;
use App\Entity\User;
use App\Repository\CommissionRepository;
use App\Repository\CommissionTemporaireRepository;
use App\Repository\NotificationCommissionRepository;
use App\Repository\NotificationCommissionTemporaireRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;

class NotificationsController extends AbstractController
{
    #[Route('/gestionNotificationCommission', name: 'gestionNotificationCommission')]
    public function gestionNotificationCommission(Request $request, EntityManagerInterface $entityManager): Response
    {
        $user = $entityManager->getRepository(User::class)->find($request->query->get('userID'));
        $commission = $entityManager->getRepository(Commission::class)->find($request->query->get('commissionID'));
        $notificationCommission = $entityManager->getRepository(NotificationCommission::class)->findByUserAndCommission($user, $commission);
        $notificationCommission[0]->setActive($request->query->get('active'));
        $entityManager->persist($notificationCommission[0]);
        $entityManager->flush();

        return $this->redirect('/commission/'.$commission->getLibelle());
    }
    #[Route('/gestionNotificationCommissionTemporaire', name: 'gestionNotificationCommissionTemporaire')]
    public function gestionNotificationCommissionTemporaire(Request $request, EntityManagerInterface $entityManager): Response
    {
        $user = $entityManager->getRepository(User::class)->find($request->query->get('userID'));
        $commissionTemporaire = $entityManager->getRepository(CommissionTemporaire::class)->find($request->query->get('commissionTemporaireID'));
        $notificationCommissionTemporaire = $entityManager->getRepository(NotificationCommissionTemporaire::class)->findByUserAndCommissionTemporaire($user, $commissionTemporaire);
        $notificationCommissionTemporaire[0]->setActive($request->query->get('active'));
        $entityManager->persist($notificationCommissionTemporaire[0]);
        $entityManager->flush();

        return $this->redirect('/commission-temporaire/'.$commissionTemporaire->getId());
    }

}

