<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class DecksController extends AbstractController {

  public function indexAction() {
    
    return $this->render('./pages/administration/decks.html.twig');
  }
}