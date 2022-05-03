<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;


class UserController extends AbstractController
{

  #[Route('/register', name:'createUser', methods:['GET','POST'])]
  public function createUser(Request $request,
                             UserRepository $userRepository,
                             UserPasswordHasherInterface $passwordHasher)
  {
    $user = new User();

    $form = $this->createForm(UserType::class, $user);
	$form->add('password', RepeatedType::class, [
	  'type' => PasswordType::class,
	  'invalid_message' => 'Mot de passe non correspondant',
	  'invalid_message_parameters' =>  [
		  "string" , 'invalid_message'
	  ],
	    'options' => [
		  'attr' => [
			  'class' => 'password-field',
		  ]
	     ],
		'first_options' => [
	        'attr' => [
		        'placeholder' => 'Mot de passe'
	        ]
        ],
		'second_options' => [
	        'attr' => [
		        'placeholder' => 'Confirmation mot de passe'
	        ]
        ],
	  'required' => true,
	]);
	$form->handleRequest($request);

      if($form->isSubmitted() && $form->isValid()){
        //recupération du mot de pass
        $plaintextPassword = $user->getPassword();
        //hachage du mot de passe
        $hashedPassword = $passwordHasher->hashPassword(
          $user,
          $plaintextPassword
        );
        //remplacement du mot de passe par le mot de passe hacher
        $user->setPassword($hashedPassword);
		//ajout du role dans l'entité
        //envoie de toutes les infos de l'entité dans la base
        $userRepository->add($user);
        return $this->redirectToRoute('showUser');
      }
      return $this->render('/user/createUser.html.twig',['userForm'=>$form->createView()]);
  }

  #[Route('/user', name:'showUser')]
  public function showUser(UserRepository $userRepository)
  {
    $users = $userRepository->findBy([],['email'=>'ASC']);
    return $this->render('/user/users.html.twig', ['users'=>$users]);
  }

  #[Route('/user/{id}/edit', name: 'app_user_edit', methods: ['GET', 'POST'])]
  public function edit(Request $request, User $user, UserRepository $userRepository): Response
  {
    $form = $this->createForm(UserType::class, $user);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
      $userRepository->add($user);
      return $this->redirectToRoute('app_user_index', [], Response::HTTP_SEE_OTHER);
    }

    return $this->renderForm('user/editUser.html.twig', [
      'user' => $user,
      'userForm' => $form,
    ]);
  }
  #[Route('/user/{id}', name: 'app_user_delete', methods: ['POST','GET'])]
  public function delete(Request $request,
                         User $user,
                         UserRepository $userRepository): Response
  {

    if ($this->isCsrfTokenValid('delete'.$user->getId(), $request->request->get('_token'))) {
      $userRepository->remove($user);
    }

    return $this->redirectToRoute('showUser', [], Response::HTTP_SEE_OTHER);
  }
}