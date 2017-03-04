<?php

namespace Veta\HomeworkBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Veta\HomeworkBundle\Entity\Tag;

class TagController extends Controller
{
    /**
     * @Route("/{_locale}/tag/{slug}", name="views", requirements={"slug": "[\w\-]+"})
     * @Method("GET")
     *
     * @param Request $request
     * @param Tag $tag
     * @return Response
     */
    public function indexAction(Request $request, Tag $tag)
    {
        $breadcrumbs = $this->get("white_october_breadcrumbs");
        $breadcrumbs->addRouteItem("Home", "veta_homework_homepage");

        $breadcrumbs->addRouteItem("Tag", "veta_homework_tag_index", ['slug' => $tag->getSlug()]);

        $breadcrumbs->addRouteItem($tag->getTitle(), "veta_homework_tag_index", ['slug' => $tag->getSlug()]);

        $query =$tag->getPosts();
        $paginator  = $this->get('knp_paginator');
        $posts = $paginator->paginate($query, $request->query->getInt('page', 1), 5);


        return $this->render('VetaHomeworkBundle:Tag:index.html.twig', [
            'posts' => $posts,
            'tag' => $tag,

        ]);
    }
}
