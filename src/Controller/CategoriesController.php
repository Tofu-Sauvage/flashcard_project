<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class CategoriesController extends AbstractController {

  public function indexAction() {
    
    return $this->render('./pages/administration/categories.html.twig');
  }
}