<?php

namespace Veta\HomeworkBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\RedirectResponse;

class PostController extends Controller
{
    /**
     * @return RedirectResponse
     */
    public function indexAction()
    {
        $breadcrumbs = $this->get("white_october_breadcrumbs");
        $breadcrumbs->addRouteItem("Home", "veta_homework_homepage");
        $breadcrumbs->addRouteItem("Post", "veta_homework_post_index");


        return $this->render('VetaHomeworkBundle:Post:index.html.twig');
    }
}
