<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class HomeController extends AbstractController {

  public function homeAction() {
    
    return $this->render("pages/accueil.html.twig");
  }
}