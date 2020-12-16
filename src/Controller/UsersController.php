<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class UsersController extends AbstractController {

  public function indexAction() {
    
    return $this->render('./pages/administration/users.html.twig');
  }
}