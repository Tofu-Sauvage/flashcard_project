<?php

namespace App\Controller;

use App\Repository\CategoryRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class CategoriesController extends AbstractController {

  public function indexAction(CategoryRepository $categoryRepository) {
    $categories = $categoryRepository->findAll();
    return $this->render('./pages/administration/categories.html.twig', ['categories'=>$categories]);
  }
}