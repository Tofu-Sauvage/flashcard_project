<?php

namespace App\Controller;

use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class UsersController extends AbstractController {

  public function indexAction(UserRepository $userRepository) {
    $users = $userRepository->findAll();
    return $this->render('./pages/administration/users.html.twig', ['users'=>$users]);
  }

  public function detailAction(UserRepository $userRepository, $id)
  {
    $user =  $userRepository->findOneBy(['id' => $id]);
    return $this->render('./pages/administration/user.html.twig', ['user' => $user]);
  }

  public function deleteAction(EntityManagerInterface $em, UserRepository $userRepository, $id)
  {
    $user = $userRepository->find($id);
    $em->remove($user);
    $em->flush();
    $this->addFlash('success-user', 'L\'utilisateur a bien été supprimé !');
    return $this->redirectToRoute('admin-users');
  }
}