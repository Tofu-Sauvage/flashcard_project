<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class DeckboardController extends AbstractController {

  public function indexAction() {

    return $this->render("base.html.twig");
  }
}