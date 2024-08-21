<?php

// src/Controller/AdminController.php

namespace App\Controller;

use App\Repository\CommissionRepository;
use App\Repository\CommissionTemporaireRepository;
use App\Repository\NotificationCommissionRepository;
use App\Repository\NotificationCommissionTemporaireRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;

class NotificationsController extends AbstractController
{
    #[Route('/gestionNotifications', name: 'gestionNotifications')]
    public function gestionNotifications(NotificationCommissionRepository $notificationCommissionRepository,
                          NotificationCommissionTemporaireRepository $notificationCommissionTemporaireRepository,
                          UserInterface $user): Response
    {
        $notificationsCommissions = $notificationCommissionRepository->findByUser($user);
        $notificationsCommissionsTemporaires = $notificationCommissionTemporaireRepository->findByUser($user);
        return $this->render('notification.html.twig', [
            'notificationsCommissions' => $notificationsCommissions,
            'notificationsCommissionsTemporaires' => $notificationsCommissionsTemporaires,
        ]);
    }
    #[Route('/updateNotifications', name: 'updateNotification', methods: ['POST'])]
    public function updateNotifications(Request $request,
                                        EntityManagerInterface $entityManager,
                                        CommissionRepository $commissionRepository,
                                        CommissionTemporaireRepository $commissionTemporaireRepository,
                                        NotificationCommissionRepository $notificationCommissionRepository,
                                        NotificationCommissionTemporaireRepository $notificationCommissionTemporaireRepository,
                                        UserInterface $user)
    {
        $entierCommission = array();
        $entierCommissionTemporaire = array();
        $test = [];
        foreach ($request->getPayload()->keys() as $key) {
            if (preg_match('/commission\\d+/', $key)) {
                preg_match('/\\d+/', $key, $idCommission);
                $commission = $commissionRepository->find($idCommission[0]);
                $notificationCommission = $notificationCommissionRepository->findByUserAndCommission($user, $commission);
                $notificationCommission[0]->setActive($request->get($key));
                $entityManager->persist($notificationCommission[0]);
                $entityManager->flush();
            }
            if (preg_match('/commissionTemporaire\\d+/', $key)) {
                preg_match('/\\d+/', $key, $idCommission);
                $commissionTemporaire = $commissionTemporaireRepository->find($idCommission[0]);
                $notificationCommissionTemporaire = $notificationCommissionTemporaireRepository->findByUserAndCommissionTemporaire($user, $commissionTemporaire);
                $notificationCommissionTemporaire[0]->setActive($request->get($key));
                $entityManager->persist($notificationCommissionTemporaire[0]);
                $entityManager->flush();
            }
        }
        return $this->redirectToRoute('gestionNotifications');
    }


}

