<?php

namespace App\Controller;

use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class UsersController extends AbstractController {

  public function indexAction(UserRepository $userRepository) {
    $users = $userRepository->findAll();
    return $this->render('./pages/administration/users.html.twig', ['users'=>$users]);
  }
}