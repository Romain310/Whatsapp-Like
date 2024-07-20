<?php

namespace App\Controller;

use App\Entity\Commission;
use App\Entity\CommissionTemporaire;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    #[Route('/', name: 'home')]
    public function index(EntityManagerInterface $entityManager): Response
    {
        $dateActuelle = date('Y-m-d');

        //Recupère la liste des commissions de la BDD
        $commissions = $entityManager->getRepository(Commission::class)->findAll();
        $commissionsTemporaireNonClos = $entityManager->getRepository(CommissionTemporaire::class)->findNonClos($dateActuelle);
        $commissionsTemporaireClos = $entityManager->getRepository(CommissionTemporaire::class)->findClos($dateActuelle);

        //Recupère objet commission général (ID 1)
        $commissionGeneral = $entityManager->getRepository(Commission::class)->find(1);

        //Récupère les messages de commission général
        $messages = $commissionGeneral->getMessages();

        //Initialise les paramètres envoyé à la vue
        $params = [
            "messages" => $commissionGeneral->getMessages(),
            "commissions" => $commissions,
            "commissionsTemporaireNonClos" => $commissionsTemporaireNonClos,
            "commissionsTemporaireClos" => $commissionsTemporaireClos,
            "dateActuelle" => $dateActuelle
        ];

        //Affiche la vue
        return $this->render('index.html.twig', $params);
    }

    #[Route('/commission/{libelleCommission}', name: 'messageCommission')]
    public function messageCommission(EntityManagerInterface $entityManager, string $libelleCommission): Response
    {
        $dateActuelle = date('Y-m-d');

        //Recupère la liste des commissions de la BDD
        $commissions = $entityManager->getRepository(Commission::class)->findAll();
        $commissionsTemporaireNonClos = $entityManager->getRepository(CommissionTemporaire::class)->findNonClos($dateActuelle);
        $commissionsTemporaireClos = $entityManager->getRepository(CommissionTemporaire::class)->findClos($dateActuelle);

        //Recupère le commission en fonction de son libelle
        $commission = $entityManager->getRepository(Commission::class)->findOneBy(array('libelle' => $libelleCommission));

        if ($commission) {
            //Initialise les paramètres envoyé à la vue
            $params = [
                "messages" => $commission->getMessages(),
                "commissions" => $commissions,
                "commissionsTemporaireNonClos" => $commissionsTemporaireNonClos,
                "commissionsTemporaireClos" => $commissionsTemporaireClos,
                "dateActuelle" => $dateActuelle
            ];
            //Affiche la vue
            return $this->render('index.html.twig', $params);
        } else {
            //Si non trouvé renvoi sur la page home
            return $this->redirectToRoute('home');
        }
    }

    #[Route('/commission-temporaire/{idCommissionTemporaire}', name: 'messageCommissionTemporaireNonClos')]
    public function messageCommissionTemporaireNonClos(EntityManagerInterface $entityManager, string $idCommissionTemporaire): Response
    {
        $dateActuelle = date('Y-m-d');

        //Recupère la liste des commissions de la BDD
        $commissions = $entityManager->getRepository(Commission::class)->findAll();
        $commissionsTemporaireNonClos = $entityManager->getRepository(CommissionTemporaire::class)->findNonClos($dateActuelle);
        $commissionsTemporaireClos = $entityManager->getRepository(CommissionTemporaire::class)->findClos($dateActuelle);

        //Recupère le commission en fonction de son libelle
        $commissionTemporaireNonClos = $entityManager->getRepository(CommissionTemporaire::class)->find($idCommissionTemporaire);

        //Vérifie si la commission n'est pas cloturé
        if ($commissionTemporaireNonClos && in_array($commissionTemporaireNonClos, $commissionsTemporaireNonClos)) {
            //Initialise les paramètres envoyé à la vue
            $params = [
                "messages" => $commissionTemporaireNonClos->getMessages(),
                "commissions" => $commissions,
                "commissionsTemporaireNonClos" => $commissionsTemporaireNonClos,
                "commissionsTemporaireClos" => $commissionsTemporaireClos,
                "dateActuelle" => $dateActuelle
            ];
            //Affiche la vue
            return $this->render('index.html.twig', $params);
        } else {
            //Si non trouvé renvoi sur la page home
            return $this->redirectToRoute('home');
        }
    }
    #[Route('/commission-clos/{idCommissionTemporaire}', name: 'messageCommissionTemporaireClos')]
    public function messageCommissionTemporaireClos(EntityManagerInterface $entityManager, string $idCommissionTemporaire): Response
    {
        $dateActuelle = date('Y-m-d');

        //Recupère la liste des commissions de la BDD
        $commissions = $entityManager->getRepository(Commission::class)->findAll();
        $commissionsTemporaireNonClos = $entityManager->getRepository(CommissionTemporaire::class)->findNonClos($dateActuelle);
        $commissionsTemporaireClos = $entityManager->getRepository(CommissionTemporaire::class)->findClos($dateActuelle);

        //Recupère le commission en fonction de son libelle
        $commissionTemporaireClos = $entityManager->getRepository(CommissionTemporaire::class)->find($idCommissionTemporaire);

        //Vérifie si la commission est cloturé
        if ($commissionTemporaireClos && in_array($commissionTemporaireClos, $commissionsTemporaireClos)) {
            //Initialise les paramètres envoyé à la vue
            $params = [
                "messages" => $commissionTemporaireClos->getMessages(),
                "commissions" => $commissions,
                "commissionsTemporaireNonClos" => $commissionsTemporaireNonClos,
                "commissionsTemporaireClos" => $commissionsTemporaireClos,
                "dateActuelle" => $dateActuelle
            ];
            //Affiche la vue
            return $this->render('index.html.twig', $params);
        } else {
            //Si non trouvé renvoi sur la page home
            return $this->redirectToRoute('home');
        }
    }
}