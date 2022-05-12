<?php

namespace App\Controller;

use App\Entity\Task;
use App\Form\TaskType;
use App\Repository\TaskRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin/todo')]
class taskController extends AbstractController
{

  #[Route('/', name:'showTask')]
  public function task(TaskRepository $taskRepository)
  {
    $tasks = $taskRepository->findAll();
    $tasksArray = [];
    foreach ($tasks as $task){
      $tasksArray[] =[
        'id'=>$task->getId(),
        'start'=>$task->getDate()->format('Y-m-d H:i:s'),
        'end'=>$task->getLimitDate()->format('Y-m-d H:i:s'),
        'title'=>$task->getType()->getName() . ' ' .$task->getClient()->getName(),
        'backgroundColor'=>$task->getBackgroundColor(),
        'borderColor'=>$task->getBorderColor(),
        'textColor'=>$task->getTextColor(),
        'allDay'=>$task->getAllDay(),
      ];
    }
    $data = json_encode($tasksArray);
    return $this->render('admin/tasks/tasks.html.twig',['data'=>$data]);
  }

  #[Route('/add',name:'addTask', methods:['GET','POST'])]
  public function addTask(Request $request,
                          TaskRepository $taskRepository)
  {
   $task = new Task();
   $form = $this->createForm(TaskType::class, $task);
   $form->handleRequest($request);
    if($form->isSubmitted() && $form->isValid()){
      if($form->get('text_color')->getData()== null){
        $task->setTextColor('#000000');
      }
      if($form->get('border_color')->getData()== null) {
        $task->setBorderColor('#FF0000');
      }
      if($form->get('background_Color')->getData()== null) {
        $task->setBackgroundColor('#FFFFFF');
      }
      $taskRepository->add($task);
      return  $this->redirectToRoute('showTask');
   }
   return $this->render('admin/tasks/addTasks.html.twig',['taskForm'=>$form->createView()]);
  }

	#[Route('/autoUpdate/{id}', name:'autoUpdate', methods: ['POST','GET'])]
  public function autoUpdate(Request $request,
                             TaskRepository $taskRepository,
                             $id)
  {
//	  get data
	  $data = json_decode($request->getContent());
	  $task = $taskRepository->findOneBy(['id' => $id]);
	   if(!empty($data->start) && empty($data->end) && empty($data->allDay)) {
		   $task->setDate(new \DateTime($data->start));
		   $taskRepository->add($task);
		   return new Response('start ok', 200);
	   }
		if(!empty($data->start) && !empty($data->end) && empty($data->allDay)) {
			$task->setDate(new \DateTime($data->start));
			$task->setLimitDate(new \DateTime($data->end));
			$taskRepository->add($task);
			return new Response('all ok', 201);
		}
	   if(!empty($data->allDay)){
		   $task->setAllDay(true);
		   $taskRepository->add($task);
		   return new Response('allDay', 203);
	   }

	  return new Response('no changes', 500);

  }

  #[Route('/delete/{id}', name:'deleteTask', methods:['POST','GET'])]
  public function delete (Request $request,
                          TaskRepository $taskRepository,
	                      $id)
  {
	  $task = $taskRepository->findOneBy(['id' => $id]);
	  $taskRepository->remove($task);
	  return new Response('Tache bien supprimer', 200);
  }
}
