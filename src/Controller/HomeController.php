<?php

namespace App\Controller;

use App\Entity\Commission;
use App\Entity\CommissionTemporaire;
use App\Entity\Message;
use App\Entity\MessageLu;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;

class HomeController extends AbstractController
{
    #[Route('/', name: 'home')]
    public function index(EntityManagerInterface $entityManager, UserInterface $user): Response
    {
        $dateActuelle = date('Y-m-d');

        //Recupère la liste des commissions de la BDD
        $commissions = $entityManager->getRepository(Commission::class)->findAll();
        $commissionsTemporaireNonClos = $entityManager->getRepository(CommissionTemporaire::class)->findNonClos($dateActuelle);
        $commissionsTemporaireClos = $entityManager->getRepository(CommissionTemporaire::class)->findClos($dateActuelle);
        $commissionsTemporaireFutur = $entityManager->getRepository(CommissionTemporaire::class)->findFutur($dateActuelle);
        //Recupère objet commission général (ID 1)
        $commissionGeneral = $entityManager->getRepository(Commission::class)->find(1);

        // On récupère la liste des commissions où les notifications sont actives
        $commissionNotifActive = $entityManager->getRepository(Commission::class)->findByUserAndActive($user, true);
        $commissionTemporaireNotifActive = $entityManager->getRepository(CommissionTemporaire::class)->findByUserAndActive($user, true);

        $messagesNonLu = $entityManager->getRepository(MessageLu::class)->findBy(['user' =>  $this->getUser()->getId(), 'lu' => false]);

        // On compte les messages non lu par commissions
        $mapNonLuByCommission = array();
        $mapNonLuByCommissionTemporaire = array();
        foreach ($messagesNonLu as $messageNonLu) {
            // Pour chaque message non lu par commission
            foreach ($messageNonLu->getMessage()->getCommissions() as $commission) {
                if ($commission == $commissionGeneral) {
                    // On efface les notifications pour cette commission
                    $messageNonLu->setLu(true);
                    $entityManager->persist($messageNonLu);
                    $entityManager->flush();
                } else {
                    if (!array_key_exists($commission->getId(), $mapNonLuByCommission)) {
                        $mapNonLuByCommission[$commission->getId()] = 0;
                    }
                    // On incremente le nombre de message
                    $mapNonLuByCommission[$commission->getId()] = $mapNonLuByCommission[$commission->getId()]+1;
                }
            }

            // Pour chaque message non lu par commission temporaire
            foreach ($messageNonLu->getMessage()->getCommissionsTemporaires() as $commissionTemporaire) {
                if (!array_key_exists($commissionTemporaire->getId(), $mapNonLuByCommissionTemporaire)) {
                    $mapNonLuByCommissionTemporaire[$commissionTemporaire->getId()] = 0;
                }
                // On incremente le nombre de message
                $mapNonLuByCommissionTemporaire[$commissionTemporaire->getId()] = $mapNonLuByCommissionTemporaire[$commissionTemporaire->getId()]+1;
            }
        }

        //Initialise les paramètres envoyé à la vue
        $params = [
            "messages" => $commissionGeneral->getMessages(),
            "commissions" => $commissions,
            "commissionsTemporaireNonClos" => $commissionsTemporaireNonClos,
            "commissionsTemporaireClos" => $commissionsTemporaireClos,
            "commissionsTemporaireFutur" => $commissionsTemporaireFutur,
            "dateActuelle" => $dateActuelle,
            "commissionSelected" => $commissionGeneral,
            "messageNonLuCommission" => $mapNonLuByCommission,
            "messageNonLuCommissionTemporaire" => $mapNonLuByCommissionTemporaire,
            "commissionNotifActive" => $commissionNotifActive,
            "commissionTemporaireNotifActive" => $commissionTemporaireNotifActive,
        ];

        //Affiche la vue
        return $this->render('index.html.twig', $params);
    }

    #[Route('/commission/{libelleCommission}', name: 'messageCommission')]
    public function messageCommission(Request $request, EntityManagerInterface $entityManager, string $libelleCommission, UserInterface $user): Response
    {
        $dateActuelle = date('Y-m-d');

        //Recupère la liste des commissions de la BDD
        $commissions = $entityManager->getRepository(Commission::class)->findAll();
        $commissionsTemporaireNonClos = $entityManager->getRepository(CommissionTemporaire::class)->findNonClos($dateActuelle);
        $commissionsTemporaireClos = $entityManager->getRepository(CommissionTemporaire::class)->findClos($dateActuelle);
        $commissionsTemporaireFutur = $entityManager->getRepository(CommissionTemporaire::class)->findFutur($dateActuelle);

        // On récupère la liste des commissions où les notifications sont actives
        $commissionNotifActive = $entityManager->getRepository(Commission::class)->findByUserAndActive($user, true);
        $commissionTemporaireNotifActive = $entityManager->getRepository(CommissionTemporaire::class)->findByUserAndActive($user, true);

        $messagesNonLu = $entityManager->getRepository(MessageLu::class)->findBy(['user' =>  $this->getUser()->getId(), 'lu' => false]);

        //Recupère le commission en fonction de son libelle
        $commissionSelected = $entityManager->getRepository(Commission::class)->findOneBy(array('libelle' => $libelleCommission));

        // On compte les messages non lu par commissions
        $mapNonLuByCommission = array();
        $mapNonLuByCommissionTemporaire = array();
        foreach ($messagesNonLu as $messageNonLu) {
            // Pour chaque message non lu par commission
            foreach ($messageNonLu->getMessage()->getCommissions() as $commission) {
                if ($commission == $commissionSelected) {
                    // On efface les notifications pour cette commission
                    $messageNonLu->setLu(true);
                    $entityManager->persist($messageNonLu);
                    $entityManager->flush();
                } else {
                    if (!array_key_exists($commission->getId(), $mapNonLuByCommission)) {
                        $mapNonLuByCommission[$commission->getId()] = 0;
                    }
                    // On incremente le nombre de message
                    $mapNonLuByCommission[$commission->getId()] = $mapNonLuByCommission[$commission->getId()]+1;
                }
            }

            // Pour chaque message non lu par commission temporaire
            foreach ($messageNonLu->getMessage()->getCommissionsTemporaires() as $commissionTemporaire) {
                if (!array_key_exists($commissionTemporaire->getId(), $mapNonLuByCommissionTemporaire)) {
                    $mapNonLuByCommissionTemporaire[$commissionTemporaire->getId()] = 0;
                }
                // On incremente le nombre de message
                $mapNonLuByCommissionTemporaire[$commissionTemporaire->getId()] = $mapNonLuByCommissionTemporaire[$commissionTemporaire->getId()]+1;
            }
        }

        if ($commissionSelected) {
            //Initialise les paramètres envoyé à la vue
            $params = [
                "messages" => $commissionSelected->getMessages(),
                "commissions" => $commissions,
                "commissionsTemporaireNonClos" => $commissionsTemporaireNonClos,
                "commissionsTemporaireClos" => $commissionsTemporaireClos,
                "commissionsTemporaireFutur" => $commissionsTemporaireFutur,
                "dateActuelle" => $dateActuelle,
                "commissionSelected" => $commissionSelected,
                "messageNonLuCommission" => $mapNonLuByCommission,
                "messageNonLuCommissionTemporaire" => $mapNonLuByCommissionTemporaire,
                "commissionNotifActive" => $commissionNotifActive,
                "commissionTemporaireNotifActive" => $commissionTemporaireNotifActive,
            ];
            //Affiche la vue
            return $this->render('index.html.twig', $params);
        } else {
            //Si non trouvé renvoi sur la page home
            return $this->redirectToRoute('home');
        }
    }

    #[Route('/commission-temporaire/{idCommissionTemporaire}', name: 'messageCommissionTemporaireNonClos')]
    public function messageCommissionTemporaireNonClos(EntityManagerInterface $entityManager, string $idCommissionTemporaire, UserInterface $user): Response
    {
        $dateActuelle = date('Y-m-d');

        //Recupère la liste des commissions de la BDD
        $commissions = $entityManager->getRepository(Commission::class)->findAll();
        $commissionsTemporaireNonClos = $entityManager->getRepository(CommissionTemporaire::class)->findNonClos($dateActuelle);
        $commissionsTemporaireClos = $entityManager->getRepository(CommissionTemporaire::class)->findClos($dateActuelle);
        $commissionsTemporaireFutur = $entityManager->getRepository(CommissionTemporaire::class)->findFutur($dateActuelle);

        //Recupère le commission en fonction de son libelle
        $commissionSelected = $entityManager->getRepository(CommissionTemporaire::class)->find($idCommissionTemporaire);

        $messagesNonLu = $entityManager->getRepository(MessageLu::class)->findBy(['user' =>  $this->getUser()->getId(), 'lu' => false]);

        // On récupère la liste des commissions où les notifications sont actives
        $commissionNotifActive = $entityManager->getRepository(Commission::class)->findByUserAndActive($user, true);
        $commissionTemporaireNotifActive = $entityManager->getRepository(CommissionTemporaire::class)->findByUserAndActive($user, true);

        // On compte les messages non lu par commissions
        $mapNonLuByCommission = array();
        $mapNonLuByCommissionTemporaire = array();
        foreach ($messagesNonLu as $messageNonLu) {
            // Pour chaque message non lu par commission
            foreach ($messageNonLu->getMessage()->getCommissions() as $commission) {
                if (!array_key_exists($commission->getId(), $mapNonLuByCommission)) {
                    $mapNonLuByCommission[$commission->getId()] = 0;
                }
                // On incremente le nombre de message
                $mapNonLuByCommission[$commission->getId()] = $mapNonLuByCommission[$commission->getId()]+1;
            }

            // Pour chaque message non lu par commission temporaire
            foreach ($messageNonLu->getMessage()->getCommissionsTemporaires() as $commissionTemporaire) {
                if ($commissionTemporaire == $commissionSelected) {
                    // On efface les notifications pour cette commission
                    $messageNonLu->setLu(true);
                    $entityManager->persist($messageNonLu);
                    $entityManager->flush();
                } else {
                    if (!array_key_exists($commissionTemporaire->getId(), $mapNonLuByCommissionTemporaire)) {
                        $mapNonLuByCommissionTemporaire[$commissionTemporaire->getId()] = 0;
                    }
                    // On incremente le nombre de message
                    $mapNonLuByCommissionTemporaire[$commissionTemporaire->getId()] = $mapNonLuByCommissionTemporaire[$commissionTemporaire->getId()]+1;
                }
            }
        }

        //Vérifie si la commission n'est pas cloturé
        if ($commissionSelected != null) {
            //Initialise les paramètres envoyé à la vue
            $params = [
                "messages" => $commissionSelected->getMessages(),
                "commissions" => $commissions,
                "commissionsTemporaireNonClos" => $commissionsTemporaireNonClos,
                "commissionsTemporaireClos" => $commissionsTemporaireClos,
                "commissionsTemporaireFutur" => $commissionsTemporaireFutur,
                "dateActuelle" => $dateActuelle,
                "commissionTemporaireSelected" => $commissionSelected,
                "messageNonLuCommission" => $mapNonLuByCommission,
                "messageNonLuCommissionTemporaire" => $mapNonLuByCommissionTemporaire,
                "commissionNotifActive" => $commissionNotifActive,
                "commissionTemporaireNotifActive" => $commissionTemporaireNotifActive,
            ];
            //Affiche la vue
            return $this->render('index.html.twig', $params);
        } else {
            //Si non trouvé renvoi sur la page home
            return $this->redirectToRoute('home');
        }
    }
    #[Route('/commission-clos/{idCommissionTemporaire}', name: 'messageCommissionTemporaireClos')]
    public function messageCommissionTemporaireClos(EntityManagerInterface $entityManager, string $idCommissionTemporaire, UserInterface $user): Response
    {
        $dateActuelle = date('Y-m-d');

        //Recupère la liste des commissions de la BDD
        $commissions = $entityManager->getRepository(Commission::class)->findAll();
        $commissionsTemporaireNonClos = $entityManager->getRepository(CommissionTemporaire::class)->findNonClos($dateActuelle);
        $commissionsTemporaireClos = $entityManager->getRepository(CommissionTemporaire::class)->findClos($dateActuelle);
        $commissionsTemporaireFutur = $entityManager->getRepository(CommissionTemporaire::class)->findFutur($dateActuelle);

        //Recupère le commission en fonction de son libelle
        $commissionSelected = $entityManager->getRepository(CommissionTemporaire::class)->find($idCommissionTemporaire);

        // On récupère la liste des commissions où les notifications sont actives
        $commissionNotifActive = $entityManager->getRepository(Commission::class)->findByUserAndActive($user, true);
        $commissionTemporaireNotifActive = $entityManager->getRepository(CommissionTemporaire::class)->findByUserAndActive($user, true);

        $messagesNonLu = $entityManager->getRepository(MessageLu::class)->findBy(['user' =>  $this->getUser()->getId(), 'lu' => false]);

        // On compte les messages non lu par commissions
        $mapNonLuByCommission = array();
        $mapNonLuByCommissionTemporaire = array();
        foreach ($messagesNonLu as $messageNonLu) {
            // Pour chaque message non lu par commission
            foreach ($messageNonLu->getMessage()->getCommissions() as $commission) {
                if (!array_key_exists($commission->getId(), $mapNonLuByCommission)) {
                    $mapNonLuByCommission[$commission->getId()] = 0;
                }
                // On incremente le nombre de message
                $mapNonLuByCommission[$commission->getId()] = $mapNonLuByCommission[$commission->getId()]+1;
            }

            // Pour chaque message non lu par commission temporaire
            foreach ($messageNonLu->getMessage()->getCommissionsTemporaires() as $commissionTemporaire) {
                if ($commissionTemporaire == $commissionSelected) {
                    // On efface les notifications pour cette commission
                    $messageNonLu->setLu(true);
                    $entityManager->persist($messageNonLu);
                    $entityManager->flush();
                } else {
                    if (!array_key_exists($commissionTemporaire->getId(), $mapNonLuByCommissionTemporaire)) {
                        $mapNonLuByCommissionTemporaire[$commissionTemporaire->getId()] = 0;
                    }
                    // On incremente le nombre de message
                    $mapNonLuByCommissionTemporaire[$commissionTemporaire->getId()] = $mapNonLuByCommissionTemporaire[$commissionTemporaire->getId()]+1;
                }
            }
        }

        //Vérifie si la commission est cloturé
        if ($commissionSelected != null) {
            //Initialise les paramètres envoyé à la vue
            $params = [
                "messages" => $commissionSelected->getMessages(),
                "commissions" => $commissions,
                "commissionsTemporaireNonClos" => $commissionsTemporaireNonClos,
                "commissionsTemporaireClos" => $commissionsTemporaireClos,
                "commissionsTemporaireFutur" => $commissionsTemporaireFutur,
                "dateActuelle" => $dateActuelle,
                "commissionTemporaireSelected" => $commissionSelected,
                "messageNonLuCommission" => $mapNonLuByCommission,
                "messageNonLuCommissionTemporaire" => $mapNonLuByCommissionTemporaire,
                "commissionNotifActive" => $commissionNotifActive,
                "commissionTemporaireNotifActive" => $commissionTemporaireNotifActive,
            ];
            //Affiche la vue
            return $this->render('index.html.twig', $params);
        } else {
            //Si non trouvé renvoi sur la page home
            return $this->redirectToRoute('home');
        }
    }

    #[Route('/sendMessage', name: 'sendMessages', methods: ['POST'])]
    public function sendMessages(EntityManagerInterface $entityManager, Request $request): RedirectResponse
    {
        $user = $entityManager->getRepository(User::class)->find($request->get('user'));
        $message = new Message();
        $message->setContenu($request->get("message"));
        $message->setDateEnvoi(new \DateTime("now"));
        $message->setUser($user);

        // Si la requête contient l'id de la commission
        if ($request->get('commission') != null) {
            $commission = $entityManager->getRepository(Commission::class)->find($request->get("commission"));
            $message->addCommission($commission);
        }

        // Si la requête contient l'id de la commission temporaire
        if ($request->get('commissionTemporaire') != null) {
            $commissionTemporaire = $entityManager->getRepository(CommissionTemporaire::class)->find($request->get("commissionTemporaire"));
            $message->addCommissionsTemporaire($commissionTemporaire);
        }

        // TODO Vérifier qu'il ne se notifie pas lui-même
        foreach ($entityManager->getRepository(User::class)->findAll() as $user) {
            if ($user != $this->getUser()) {
                $messageNonLu = new MessageLu();
                $messageNonLu->setUser($user);
                $messageNonLu->setMessage($message);
                // On indique que tous les autres users n'ont pas lu le message
                $messageNonLu->setLu(false);
                $message->addUserReader($messageNonLu);
            }
        }

        $entityManager->persist($message);
        $entityManager->flush();
        if (!$message->getCommissions()->isEmpty()) {
            return $this->redirect('/commission/'.$message->getCommissions()[0]->getLibelle());
        } elseif (!$message->getCommissionsTemporaires()->isEmpty()) {
            return $this->redirect('/commission-temporaire/'.$message->getCommissionsTemporaires()[0]->getId());
        } else {
            return $this->redirect('/');
        }
    }

//    #[Route('/sendNotifications', name: 'sendNotifications')]
//    public function sendNotifications(Request $request, NotifierInterface $notifier): Response
//    {
//        $notification = (new Notification('New Invoice', ['browser']))
//            ->content('You got a new invoice for 15 EUR.');
//
//        // The receiver of the Notification
//        $recipient = new Recipient(
//            "valentin.simon@limayrac.fr",
//            "0617205483"
//        );
//
//        // Send the notification to the recipient
//        $notifier->send($notification, $recipient);
//        return $this->render('test.html.twig');
//    }
}