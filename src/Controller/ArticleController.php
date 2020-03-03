<?php

namespace App\Controller;

use App\Entity\Article;
use App\Form\ArticleType;
use App\Repository\ArticleRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ArticleController extends AbstractController
{
    private $articleRepository;
    public function __construct(ArticleRepository $articleRepository){
        $this->articleRepository = $articleRepository;
    }
    /**
     * @Route("/article-list", name="article-list")
     */
    public function index(){
        $articleList = $this->articleRepository->findAll();   
        return $this->render('article/index.html.twig', [
            'articleList' => $articleList,
            ]);
        }

    /**
     * @Route("/article-create", name="article-create")
    */
    
    public function createArticle(Request $request): Response{
        $article = new Article();
        $form = $this->createForm( ArticleType::class, $article);
        $form->handleRequest($request);   
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($article);
            $entityManager->flush();       
            return $this->redirectToRoute('article-list');
        }
        return $this->render('article/form.html.twig', ['form' => $form->createView(),]);
    }

     /**
     * @Route("/article_profile/{id}", name="article_profile")
    */
    public function showUser(int $id, Request $request){
        $article = $this->articleRepository->find($id);
        $form = $this->createForm(ArticleType::class, $article);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $entityManger = $this->getDoctrine()->getManager();
            $entityManger->persist($article);
            $entityManger->flush();
            $this->addFlash('notification', "L'article a bien été modifié !");
        }
        return $this->render('article/article_profile.html.twig', [
            'article' => $article,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/delete_article/{id}", name="delete_article")
     */
    public function deleteUser(Article $article){
        $entityManger = $this->getDoctrine()->getManager();
        $entityManger->remove($article);
        $entityManger->flush();
        return $this->redirectToRoute("user-list");
    }
    
}
