<?php

namespace App\Controller;

use App\Entity\Clients;
use App\Entity\Media;
use App\Form\ClientsType;
use App\Repository\ClientsRepository;
use App\Repository\MediaRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Constraints\Date;

#[Route('/admin/client')]
class clientController extends AbstractController
{
  //Show All my clients on the route /admin/client
  #[Route('/', name:'showClient')]
  public function show (ClientsRepository $clientsRepository)
  {
    //find all my "active" clients. And
    $clients = $clientsRepository->findBy(['isActive' => true],['Name' => 'ASC']);
    $ages= [];
	$phone = [];
    //    Get age for each client
    foreach ($clients as $client){
	  $oldClientPhone = $client->getTel();
	  $today = new \DateTime('now');
      $clientBirth = $client->getAge();
      $diff = date_diff($today,$clientBirth);
      $clientAge = $diff->format('%y');
      $ages[$client->getId()]=$clientAge;
	  if(strlen($oldClientPhone) === 10){
		  $newClientPhone = substr($oldClientPhone, 0, 2) .' ';
		  $newClientPhone .= substr($oldClientPhone, 2, 2) .' ';
		  $newClientPhone .= substr($oldClientPhone, 4, 2) .' ';
		  $newClientPhone .= substr($oldClientPhone, 6, 2) .' ';
		  $newClientPhone .= substr($oldClientPhone, 8, 2) .' ';
	  }else{
		  $newClientPhone = $oldClientPhone;
	  }
	  $phone[$client->getId()]=$newClientPhone;
    }
    return $this->render('admin/clients/clients.html.twig', ['clients' => $clients, 'ages' => $ages, 'phone' => $phone]);
  }

  //edit on of my client on route /adlin/client/edit/id wehere id passed on "post" by a form
  #[Route('/edit/{id}', name:'editClient', methods: ['GET','POST'])]
  public function edit ($id,
                        ClientsRepository $clientsRepository,
                        MediaRepository $mediaRepository,
                        Request $request)
  {
    //find the client
    $client = $clientsRepository->findOneBy(['id'=>$id]);
    //get img information
    $currentImage = $client->getImg();
    //creat a form with the class parameter Client and information parameter $client
    $form = $this->createForm(ClientsType::class, $client);
    $form->handleRequest($request);
    //check if the form is submit and good
      if ($form->isSubmitted() && $form->isValid()){
        //if there is an image on the form
        if($form->get('img')->getData() != null){
          //find the client img
          $media = $mediaRepository->findOneBy(['id'=>$currentImage]);
          //catch img of the form and do some title traitement for my DB
          $file = $form->get('img')->getData();
          $fileName = md5(uniqid()) . '.' . $file->guessExtension();
          $file->move($this->getParameter('upload_directory'), $fileName);
          //set the new name on request
          $media->setName($fileName);
          //pull request on my DB
          $mediaRepository->add($media);
        }
        //Pull all info of the form on my DB
        $clientsRepository->add($client);
//        redirect to my show function
        return $this->redirectToRoute('showClient');
      }
    return $this->render('admin/clients/editClient.html.twig', ['clientForm'=> $form->createView(), 'currentImg'=>$currentImage]);
  }

  //function for add one client

	/**
	 * @throws \Doctrine\ORM\OptimisticLockException
	 * @throws \Doctrine\ORM\ORMException
	 */
	#[Route('/add', name:'addClient', methods:['GET', 'POST'])]
  public function add (Request $request,
                       MediaRepository $mediaRepository,
                       ClientsRepository $clientsRepository)
  {
    //creat a new instance of client class and Media class for take info and put on db
    $client = new Clients();
    $media = new Media();

//    Create a form
    $form =$this->createForm(ClientsType::class, $client);
    $form->handleRequest($request);
//    if form submited and is valid and take all info and put on a request for db
    if($form->isSubmitted() && $form->isValid()){
//      if their is an image on the fomr modelate for db
      if($form->get('img')->getData()) {
        $file = $form->get('img')->getData();
        $fileName = md5(uniqid()) . '.' . $file->guessExtension();
        $file->move($this->getParameter('upload_directory'), $fileName);
        $media->setName($fileName);
        $mediaRepository->add($media);
        $client->setImg($media);
      }
//      set client active
      $client->setIsActive(1);
//      and put client on db
      $clientsRepository->add($client);
//      redirect to the show function
      return $this->redirectToRoute('showClient');
    }
//    their is no form submited so redirect to the page for the form
    return $this->render('admin/clients/addClient.html.twig', ['clientForm'=> $form->createView()]);
  }

  #[Route('/disable/{id}', name:'disableClient')]
  public function disableClient($id,
                                ClientsRepository $clientsRepository)
  {
    //find the client
    $client = $clientsRepository->findOneBy(['id'=>$id]);
//    set the client inactive on DB
    $client->setIsActive(0);
    $clientsRepository->add($client);
	  return new Response('clientArchived', 201);
  }

  #[Route('/delete/{id}', name:'deletClient')]
  public function deleteClient($id,
                               ClientsRepository $clientsRepository,
                               MediaRepository $mediaRepository)
  {
    //find the client
    $client = $clientsRepository->findOneBy(['id'=>$id]);
	$img = $mediaRepository->findOneBy(['id'=>0]);
//    set the client inactive on DB
    $client->setIsActive(0)
		->setAge(new \DateTime())
		->setImg($img)
		->setName("")
		->setAdress("")
		->setMail("")
		->setTel("");
    $clientsRepository->add($client);
    return new Response('ClientDelete', 202);
  }

  #[Route('/archived', name:'clientsArchived')]
  public function clientsArchived(ClientsRepository $clientsRepository)
  {
	  //find all my "inactive" clients.
	  $clients = $clientsRepository->findArchivedClient();
	  $ages= [];
	  //    Get age for each client

	  foreach ($clients as $client){
		  $today = new \DateTime('now');
		  $clientBirth = new \DateTime($client['age']);
		  $diff = date_diff($today,$clientBirth);
		  $clientAge = $diff->format('%y');
		  $ages[$client['id']]=$clientAge;
	  }
//	  Return to the vue
	  return $this->render('admin/clients/clientsArchived.html.twig', ['clients' => $clients, 'ages'=>$ages]);
  }

  #[Route('/restaure/{id}', name:'restaureClient')]
  public function restaureClient($id,
                                 ClientsRepository $clientsRepository)
  {
	  //find the client
	  $client = $clientsRepository->findOneBy(['id'=>$id]);
//    set the client inactive on DB
	  $client->setIsActive(1);
	  $clientsRepository->add($client);
	  return new Response('Client Restaur??', 201);
  }
}