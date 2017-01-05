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

        $em = $this->getDoctrine()->getManager();
        $posts = $em->getRepository('VetaHomeworkBundle:Post')->findMostRecent($limit = 5);
        $all_posts = $em->getRepository('VetaHomeworkBundle:Post')->findMostRecent();

        return $this->render('VetaHomeworkBundle:Site:index.html.twig', [
            'posts' => $posts,
            'all_posts' => $all_posts,

        ]);
    }
}
