<?php

namespace Veta\HomeworkBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TagController extends Controller
{
    /**
     * View data Tag
     *
     * @Route("/tag/{slug}", name="views", requirements={"slug": "[\w\-]+"})
     * @Method("GET")
     *
     * @param Request $request
     * @return Response
     */
    public function indexAction(Request $request)
    {
        $slug = $request->attributes->get('slug');
        $em = $this->getDoctrine()->getManager();
        $tag = $em->getRepository('VetaHomeworkBundle:Tag')->findOneBy(['slug' => $slug]);

        $breadcrumbs = $this->get("white_october_breadcrumbs");
        $breadcrumbs->addRouteItem("Home", "veta_homework_homepage");

        $breadcrumbs->addRouteItem("Tag", "veta_homework_tag_index", ['slug' => $slug]);

        $breadcrumbs->addRouteItem($tag->getTitle(), "veta_homework_tag_index", ['slug' => $slug]);

        $all_posts = $em->getRepository('VetaHomeworkBundle:Post')->findMostRecent();
        $query =$tag->getPosts();
        $paginator  = $this->get('knp_paginator');
        $posts = $paginator->paginate($query, $request->query->getInt('page', 1), 5);


        return $this->render('VetaHomeworkBundle:Tag:index.html.twig', [
            'all_posts' => $all_posts,
            'posts' => $posts,

        ]);
    }
}
