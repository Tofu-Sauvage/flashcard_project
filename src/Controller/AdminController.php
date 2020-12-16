<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AdminController extends AbstractController
{
  public function indexAction()
  {
    return $this->render('./pages/administration/dashboard.html.twig');
  }
}
