<?php

namespace App\Controller;

use App\Entity\Message;
use App\Form\ContactType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;

class MessageController extends AbstractController
{
  public function messageCreateAction(Request $request,EntityManagerInterface $em)
  {
    $form = $this->createForm(ContactType::class);
    $form->handleRequest($request);
    if ($form->isSubmitted()) {
      $message = new Message();
      $message = $form->getData();
      $em->persist($message);
      $em->flush();
      $this->addFlash('success-message', 'Votre message a été envoyé avec succès ! (avec qui ?)');
    }

    return $this->render('./pages/contactForm.html.twig', ['formulaire' => $form->createView()]);
  }

}
