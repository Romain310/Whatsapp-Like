<?php

namespace App\Controller;

use App\Entity\Commission;
use App\Entity\CommissionTemporaire;
use App\Entity\NotificationCommission;
use App\Entity\NotificationCommissionTemporaire;
use App\Entity\User;
use App\Form\RegistrationFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;

class RegistrationController extends AbstractController
{
    #[Route('/register', name: 'app_register')]
    public function register(Request $request, UserPasswordHasherInterface $userPasswordHasher, Security $security, EntityManagerInterface $entityManager): Response
    {
        $user = new User();
        $user->setRoles(['ROLE_USER']);
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // On active les notifs pour toutes les commissions
            foreach ($entityManager->getRepository(Commission::class)->findAll() as $commission) {
                $notificationsCommissions = new NotificationCommission();
                $notificationsCommissions->setCommission($commission);
                $notificationsCommissions->setUser($user);
                // Par défaut toutes les notifs sont actives
                $notificationsCommissions->setActive(true);
                $user->addNotificationsCommission($notificationsCommissions);
            }
            // On active les notifs pour toutes les commissions temporaires
            foreach ($entityManager->getRepository(CommissionTemporaire::class)->findNonClos(new \DateTime("now")) as $commissionTemporaire) {
                $notificationsCommissionsTemporaires = new NotificationCommissionTemporaire();
                $notificationsCommissionsTemporaires->setCommissionTemporaire($commissionTemporaire);
                $notificationsCommissionsTemporaires->setUser($user);
                // Par défaut toutes les notifs sont actives
                $notificationsCommissionsTemporaires->setActive(true);
                $user->addNotificationsCommissionsTemporaire($notificationsCommissionsTemporaires);
            }
            // encode the plain password
            $user->setPassword(
                $userPasswordHasher->hashPassword(
                    $user,
                    $form->get('plainPassword')->getData()
                )
            );

            $entityManager->persist($user);
            $entityManager->flush();

            // do anything else you need here, like send an email

            return $security->login($user, 'form_login', 'main');
        }

        return $this->render('registration/register.html.twig', [
            'registrationForm' => $form,
        ]);
    }
}
