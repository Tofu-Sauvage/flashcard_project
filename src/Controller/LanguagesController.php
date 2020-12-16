<?php

namespace App\Controller;

use App\Repository\LanguageRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class LanguagesController extends AbstractController {

  public function indexAction(LanguageRepository $languageRepository) {
    $languages = $languageRepository->findAll();
    return $this->render('./pages/administration/languages.html.twig', ['languages'=>$languages]);
  }
}