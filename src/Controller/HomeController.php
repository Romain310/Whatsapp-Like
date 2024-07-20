<?php

namespace App\Controller;

use App\Entity\Commission;
use App\Entity\CommissionTemporaire;
use App\Entity\Message;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Exception\ORMException;
use Doctrine\ORM\OptimisticLockException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
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

        //Initialise les paramètres envoyé à la vue
        $params = [
            "messages" => $commissionGeneral->getMessages(),
            "commissions" => $commissions,
            "commissionsTemporaireNonClos" => $commissionsTemporaireNonClos,
            "commissionsTemporaireClos" => $commissionsTemporaireClos,
            "dateActuelle" => $dateActuelle,
            "commissionSelected" => $commissionGeneral
        ];

        //Affiche la vue
        return $this->render('index.html.twig', $params);
    }

    #[Route('/commission/{libelleCommission}', name: 'messageCommission')]
    public function messageCommission(Request $request, EntityManagerInterface $entityManager, string $libelleCommission): Response
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
                "dateActuelle" => $dateActuelle,
                "commissionSelected" => $commission
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
        $commissionSelected = $entityManager->getRepository(CommissionTemporaire::class)->find($idCommissionTemporaire);

        //Vérifie si la commission n'est pas cloturé
        if ($commissionSelected != null) {
            //Initialise les paramètres envoyé à la vue
            $params = [
                "messages" => $commissionSelected->getMessages(),
                "commissions" => $commissions,
                "commissionsTemporaireNonClos" => $commissionsTemporaireNonClos,
                "commissionsTemporaireClos" => $commissionsTemporaireClos,
                "dateActuelle" => $dateActuelle,
                "commissionTemporaireSelected" => $commissionSelected
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
        $commissionSelected = $entityManager->getRepository(CommissionTemporaire::class)->find($idCommissionTemporaire);

        //Vérifie si la commission est cloturé
        if ($commissionSelected != null) {
            //Initialise les paramètres envoyé à la vue
            $params = [
                "messages" => $commissionSelected->getMessages(),
                "commissions" => $commissions,
                "commissionsTemporaireNonClos" => $commissionsTemporaireNonClos,
                "commissionsTemporaireClos" => $commissionsTemporaireClos,
                "dateActuelle" => $dateActuelle,
                "commissionTemporaireSelected" => $commissionSelected
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
}