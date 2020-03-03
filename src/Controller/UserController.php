<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserFormType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class UserController extends AbstractController
{
    private $userRepository;
    public function __construct(UserRepository $userRepository){
        $this->userRepository = $userRepository;
    }
    /**
     * @Route("/user-list", name="user-list")
     */
    public function index(){
        $userList = $this->userRepository->findAll();   
        return $this->render('user/index.html.twig', [
            'userList' => $userList,
            ]);
        }

    /**
     * @Route("/user-create", name="user-create")
    */
    
    public function createUserAction(Request $request): Response{
        $user = new User();
        $form = $this->createForm( UserFormType::class, $user);
        $form->handleRequest($request);   
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();       
            return $this->redirectToRoute('user-list');
        }
        return $this->render('user/new.html.twig', ['form' => $form->createView(),]);
    }

    /**
     * @Route("/user_profile/{id}", name="user_profile")
    */
    public function showUser(int $id, Request $request){
        $user = $this->userRepository->find($id);
        $form = $this->createForm(UserFormType::class, $user);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $entityManger = $this->getDoctrine()->getManager();
            $entityManger->persist($user);
            $entityManger->flush();
            $this->addFlash('notification', "L'utilisateur a bien été modifié !");
        }
        return $this->render('user/user_profile.html.twig', [
            'user' => $user,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/delete_user/{id}", name="delete_user")
     */
    public function deleteUser(User $user){
        $entityManger = $this->getDoctrine()->getManager();
        $entityManger->remove($user);
        $entityManger->flush();
        return $this->redirectToRoute("user-list");
    }
}
