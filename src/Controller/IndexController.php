<?php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Article;
use App\Entity\Image;
use App\Entity\Partenaire;
use App\Form\ImageType;
use Symfony\Component\HttpFoundation\Request;

class IndexController extends Controller
{
	/**
     * @Route("/", name="index")
     */
    public function indexAction()
    {
		$em = $this->getDoctrine()->getManager();
		//$articleAttached=$em->getRepository(Article::class)->find(25);
		//$articles=$em->getRepository(Article::class)->findByWithoutId(25);
		$articles=$em->getRepository(Article::class)->findBy(array(), array('id' => 'desc'), 3, 0);
		$partenaires=$em->getRepository(Partenaire::class)->findAll();

		return $this->render('index/index.html.twig',array('articles'=>$articles,'partenaires'=>$partenaires));
        //return $this->render('index/index.html.twig',array('articleAttached'=>$articleAttached,'articles'=>$articles,'partenaires'=>$partenaires));
    }	

    /**
     * @Route("/legal", name="legal")
     */
    public function legal()
    {
        return $this->render('index/legal.html.twig');
    }
	
	/**
     * @Route("/article/{id}", requirements={
	 *     "id": "\d+"
	 * }, name="article")
     */
    public function articleAction($id)
    {
		$em = $this->getDoctrine()->getManager();
		$article=$em->getRepository(Article::class)->find($id);
		
		if (!$article) {
			throw $this->createNotFoundException('Pas d\'article pour l\'id '.$id);
		}
		
        return $this->render('index/article.html.twig',array('article'=>$article));
    }		

	/**
     * @Route("/articles", name="articles")
     */
    public function articlesAction(Request $request)
    {
		$dql   = "SELECT a FROM App:Article a ORDER BY a.id DESC";
		$em = $this->getDoctrine()->getManager();
		$query = $em->createQuery($dql);

		$paginator  = $this->get('knp_paginator');
		$pagination = $paginator->paginate(
			$query, /* query NOT result */
			$request->query->getInt('page', 1)/*page number*/,
			5/*limit per page*/
		);

		// parameters to template
		return $this->render('index/list.html.twig', array('pagination' => $pagination));
    }	

	
	/**
     * @Route("/decouvrir", name="decouvrir")
     */
    public function decouvrirAction()
    {
        return $this->render('index/decouvrir.html.twig');
    }		
	
	/**
     * @Route("/galerie", name="galerie")
     */
    public function galerieAction(Request $request)
    {
		$dql   = "SELECT i FROM App:Image i ORDER BY i.id DESC";
		$em = $this->getDoctrine()->getManager();
		$query = $em->createQuery($dql);

		$paginator  = $this->get('knp_paginator');
		$pagination = $paginator->paginate(
			$query, /* query NOT result */
			$request->query->getInt('page', 1)/*page number*/,
			20/*limit per page*/
		);
		
        return $this->render('index/galerie.html.twig', array('pagination' => $pagination));
    }	
}
