<?php

namespace Veta\HomeworkBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class CategoryController extends Controller
{
    /**
     * View data Category
     *
     * @Route("/category/{slug}", name="views", requirements={"slug": "[\w\-]+"})
     * @Method("GET")
     *
     * @param Request $request
     * @return Response
     */
    public function indexAction(Request $request)
    {
        $slug = $request->attributes->get('slug');
        $em = $this->getDoctrine()->getManager();
        $category = $em->getRepository('VetaHomeworkBundle:Category')->findOneBy(['slug' => $slug]);

        $repo = $em->getRepository('VetaHomeworkBundle:Category');
        $categories = $repo->getNodesHierarchy();

        $breadcrumbs = $this->get("white_october_breadcrumbs");
        $breadcrumbs->addRouteItem("Home", "veta_homework_homepage");

        $breadcrumbs->addRouteItem("Category", "veta_homework_category_index", ['slug' => $category->getRoot()->getSlug()]);

        if (($category->getRoot() != $category->getTitle()) && ($category->getRoot() != $category->getParent())) {
            $breadcrumbs->addRouteItem($category->getRoot(), "veta_homework_category_index", ['slug' => $category->getRoot()->getSlug()]);
        }

        if (($category->getParent() != $category->getTitle()) && ($category->getParent() != null)) {
            $breadcrumbs->addRouteItem($category->getParent(), "veta_homework_category_index", ['slug' => $category->getParent()->getSlug()]);
        }

        $breadcrumbs->addRouteItem($category->getTitle(), "veta_homework_category_index", ['slug' => $slug]);

        $all_posts = $em->getRepository('VetaHomeworkBundle:Post')->findMostRecent();
        $query = $category->getPosts();
        $paginator  = $this->get('knp_paginator');
        $posts = $paginator->paginate($query, $request->query->getInt('page', 1), 1);


        return $this->render('VetaHomeworkBundle:Category:index.html.twig', [
            'categories' => $categories,
            'all_posts' => $all_posts,
            'posts' => $posts,

        ]);
    }
}
