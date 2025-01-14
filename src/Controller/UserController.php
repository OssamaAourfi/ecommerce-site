<?php

namespace App\Controller;

use App\Repository\UserRepository;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class UserController extends AbstractController
{
    #[Route('/admin/user', name: 'app_user')]
    public function index(UserRepository $userRepository): Response
    {
        $users = $userRepository->findAll();
        return $this->render('user/index.html.twig', [
            'users' => $users
        ]);
    }
#[Route('/admin/user/{id}/to/editor', name: 'app_user_to_editor')]
public function changeRole(EntityManagerInterface $em,User $user):Response{
        $user->setRoles(["ROLER_EDITOR","ROLE_USER"]);
        $em->flush();
        $this->addFlash('success','le role editeur a ete ajouter avec succes');
        return $this->redirectToRoute('app_user');
}
    #[Route('/admin/user/{id}/remove/editor', name: 'app_user_remove_editor_role')]
    public function removeRole(EntityManagerInterface $em,User $user):Response{
        $user->setRoles([]);
        $em->flush();
        $this->addFlash('danger','le role editeur a ete retirer');
        return $this->redirectToRoute('app_user');
    }
    #[Route('/admin/user/{id}/remove', name: 'user_remove')]
    public function removeUser(EntityManagerInterface $em,$id,UserRepository $userRepository):Response{
        $userFind = $userRepository->find($id);
        $em->remove($userFind);
        $em->flush();
        $this->addFlash('success','utilisateur supprimer avec success');
        return $this->redirectToRoute('app_user');
    }
}
