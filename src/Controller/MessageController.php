<?php

namespace App\Controller;

use App\Entity\Message;
use App\Form\ContactType;
use App\Repository\MessageRepository;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class MessageController extends AbstractController
{
  /* Afficher l'intégralité des messages de contact dans la vue admin */
  public function indexAction(MessageRepository $messageRepository, Request $request, PaginatorInterface $paginator) {
    $messagesTable = $messageRepository->findAll();

    $limit = 10; 
    $firstPage = 1;

    $messages = $paginator->paginate(
        $messagesTable, 
        $request->query->getInt('page', $firstPage),
        $limit
    );
    return $this->render('./pages/administration/messages.html.twig', ['messages'=>$messages, 'messagesTable'=>$messagesTable]);
  }

  /* Afficher le formulaire de contact */
  public function messageCreateAction(Request $request,EntityManagerInterface $em)
  {
    $form = $this->createForm(ContactType::class);
    $form->handleRequest($request);
    if ($form->isSubmitted()) {
      $message = new Message();
      $message = $form->getData();
      $message->setCreatedAt(new DateTime('now'));
      $em->persist($message);
      $em->flush();

        if ($this->getUser()) {

          if(in_array( 'ROLE_ADMIN', $this->getUser()->getRoles() )) {
              $route = 'admin-dashboard';
          } elseif (in_array( "ROLE_USER", $this->getUser()->getRoles() )) {
              $route = 'deckboard';
          } 
          $this->addFlash('success-message', 'Votre message a été envoyé avec succès ! (avec qui ?)');
          return $this->redirectToRoute($route);
        } else {
          $this->addFlash('success-message', 'Votre message a été envoyé avec succès ! (avec qui ?)');
          return $this->redirectToRoute('index');
        }
    }
    return $this->render('./pages/contactForm.html.twig', ['formulaire' => $form->createView()]);
  }
}