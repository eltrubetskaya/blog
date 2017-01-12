<?php

namespace Veta\HomeworkBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

class SiteController extends Controller
{
    /**
     * @return Response
     */
    public function indexAction()
    {
        $breadcrumbs = $this->get("white_october_breadcrumbs");
        $breadcrumbs->addRouteItem("Home", "veta_homework_homepage");

        $posts = $this->getDoctrine()->getRepository('VetaHomeworkBundle:Post')->findMostRecent($limit = 5);
        $postsSidebarModule = $this->getDoctrine()->getRepository('VetaHomeworkBundle:Post')->findMostRecent();

        return $this->render('VetaHomeworkBundle:Site:index.html.twig', [
            'posts' => $posts,
            'postsSidebarModule' => $postsSidebarModule,

        ]);
    }
}
