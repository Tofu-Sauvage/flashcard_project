<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class CardsController extends AbstractController {

  public function indexAction() {
    
    return $this->render('./pages/administration/cards.html.twig');
  }
}