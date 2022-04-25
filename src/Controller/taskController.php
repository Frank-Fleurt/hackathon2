<?php

namespace App\Controller;

use App\Entity\Task;
use App\Form\TaskType;
use App\Repository\TaskRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
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

  #[Route('/edit', name:'editTask', methods:['GET','POST'])]
  public function editTask(Task $task,
                           Request $request,
                           TaskRepository $taskRepository)
  {
    $form = $this->createForm(TaskType::class, $task);
    $form->handleRequest($request);
    if($form->isSubmitted() && $form->isValid()){
      $taskRepository->add($task);
      return  $this->redirectToRoute('showTask');
    }
    return $this->render('admin/tasks/editTasks.html.twig',['taskForm'=>$form->createView()]);
  }

  #[Route('/{id}', name: 'app_user_delete', methods: ['POST','GET'])]
  public function delete(Request $request,
                         Task $task,
                         TaskRepository $taskRepository)
  {

    if ($this->isCsrfTokenValid('delete'.$task->getId(), $request->request->get('_token'))) {
      $taskRepository->remove($task);
    }

    return $this->redirectToRoute('showTask', [], Response::HTTP_SEE_OTHER);
  }
}