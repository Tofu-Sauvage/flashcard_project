<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\InscriptionType;
use App\Repository\LanguageRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class HomeController extends AbstractController {

  private $encoder;

  public function __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->encoder = $encoder;
    }

  public function homeAction(Request $request, EntityManagerInterface $em, LanguageRepository $languageRepository, UserRepository $userRepository) {
    $user = new User();
    $form = $this->createForm(InscriptionType::class);
    
    $form->handleRequest($request);

    if($form->isSubmitted() && $form->isValid()){

      $user = $form->getData();

      $test = $userRepository->findOneBy(['email' => $user->getEmail()]);

      if($test) {
        $this->addFlash('error', "Ce mail est déja utilisé !");
      return $this->redirectToRoute('index');
      }

      $image = $form->get('image')->getData();
      
      if($image){
        $newFileName ="user-image" . uniqid(). "." . $image->guessExtension();
        $image->move($this->getParameter('uploads'), $newFileName);
        $user->setImage($newFileName);
      } else {
        $user->setImage('lapin.jpg'); // Image "blanche" de lapin par défaut
      }

      $user->setCreatedAt(new \DateTime()); // Date et heure au moment de la création
      $user->setRoles(['ROLE_USER']);
      $language = $languageRepository->findOneBy(['name' => 'Français']); // Français par défaut
      $user->setLanguageNative($language);
      $user->setPassword($this->encoder->encodePassword($user, $form->get('password')->getData()));

      $em->persist($user);
      $em->flush();
      
      $this->addFlash('success', "Vous êtes bien inscrit. Connectez-vous !");
      return $this->redirectToRoute('index');
    }
    return $this->render("pages/accueil.html.twig", ['form' => $form->createView(), 'errors' => $form->getErrors()]);
  }
}