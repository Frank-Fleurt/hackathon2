<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use function Automattic\Jetpack\Extensions\Business_Hours\render;

class HomeController extends AbstractController
{
  #[Route('/', name:'home')]
  public function home ()
  {
    return $this->render('general/home.html.twig', ['section'=>'']);
  }
}