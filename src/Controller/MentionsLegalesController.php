<?php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class MentionsLegalesController extends AbstractController
{
  public function indexAction()
  {
    return $this->render('./pages/mentionsLegales.html.twig');
  }  
  
}