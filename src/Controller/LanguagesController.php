<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class LanguagesController extends AbstractController {

  public function indexAction() {
    
    return $this->render('./pages/administration/languages.html.twig');
  }
}