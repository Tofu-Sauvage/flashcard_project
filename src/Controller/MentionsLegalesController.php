<?php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class MentionsLegalesController extends AbstractController
{
  /* Vue user : affiche les mentions légales (fallait-il vraiment le préciser ?) */
  public function indexAction()
  {
    return $this->render('./pages/mentionsLegales.html.twig');
  }  
  
}