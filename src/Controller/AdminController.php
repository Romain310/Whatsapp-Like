<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

class AdminController extends AbstractController
{
    #[Route('/admin', name: 'admin')]
    public function index(UserRepository $userRepository): Response
    {
        $users = $userRepository->findAll();

        return $this->render('admin.html.twig', [
            'users' => $users,
        ]);
    }

    #[Route('/admin/delete/{id}', name: 'admin_delete')]
    public function delete(User $user, EntityManagerInterface $em, Request $request): Response
    {
        // Vérifie le token CSRF
        if ($this->isCsrfTokenValid('delete-user-' . $user->getId(), $request->request->get('_token'))) {
            $em->remove($user);
            $em->flush();

            $this->addFlash('success', 'Utilisateur supprimé avec succès.');
        }

        return $this->redirectToRoute('admin');
    }

    #[Route('/admin/edit/{id}', name: 'admin_edit')]
    public function edit(User $user, Request $request, EntityManagerInterface $em): Response
    {
        // Vérifie le token CSRF
        if ($this->isCsrfTokenValid('edit-user-' . $user->getId(), $request->request->get('_token'))) {
            $user->setNom($request->request->get('nom'));
            $user->setPrenom($request->request->get('prenom'));
            $user->setMail($request->request->get('mail'));

            // Utiliser 'request->get()' avec 'roles' en tant que tableau
            $user->setRoles($request->request->all('roles')); // Spécifie que 'roles' est un tableau

            $em->persist($user);
            $em->flush();

            $this->addFlash('success', 'Utilisateur modifié avec succès.');
        }

        return $this->redirectToRoute('admin');
    }
}
