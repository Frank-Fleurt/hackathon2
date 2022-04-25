<?php

namespace App\Controller;

use App\Entity\Task;
use App\Repository\TaskRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ApiController extends AbstractController
{
    #[Route('/api/{id}/edit', name: 'api_event_edit', methods:['PUT'])]
    public function mahjEvent(?Task $task,
                              Request $request,
                              TaskRepository $taskRepository): Response
    {
      $donnees = json_decode($request->getContent());

      if (
        isset($donnees->title) && !empty($donnees->title) &&
        isset($donnees->date) && !empty($donnees->date) &&
        isset($donnees->description) && !empty($donnees->description) &&
        isset($donnees->backgroundColor) && !empty($donnees->backgroundColor) &&
        isset($donnees->bodercolor) && !empty($donnees->boderColor) &&
        isset($donnees->textColor) && !empty($donnees->textColor)
      ){
        $task ->setDate(new \DateTime($donnees->date));
        if($donnees->allDay){
          $task ->setLimitDate(new \DateTime($donnees->date));
        }else{
          $task ->setLimitDate(new \DateTime($donnees->limitDate));
        }

        $taskRepository->add($task);

//        retourne le code
        return new Response('Ok', $code);
    }else{
//       les donnes sont pas complètes
        return new Response('données inxomplètes', 404);
      }

      return $this->render('api/index.html.twig', [
          'controller_name' => 'ApiController',
      ]);
    }
}
