<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\InscriptionType;
use App\Service\FormsManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

class HomeController extends AbstractController {

  public function homeAction(Request $request, EntityManagerInterface $em) {
    $user = new User();
    $form = $this->createForm(InscriptionType::class);

    $form->handleRequest($request);
    if($form->isSubmitted()){
      $user = $form->getData();
      $image = $form->get('image')->getData();

      if($image){
        $newFileName ="user-" . uniqid(). "." . $image->guessExtension();
        $image->move($this->getParameter('uploads'), $newFileName);
        $user->setImage($newFileName);
      }

      $user->setCreatedAt(date('now'));
      $user->setRoles(['ROLE_USER']);

        $em->persist($user);
        $em->flush();
        return $this->redirectToRoute('index');
    }
    return $this->render("pages/accueil.html.twig", ['form' => $form->createView()]);
  }
}