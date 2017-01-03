<?php

namespace Veta\HomeworkBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\RedirectResponse;

class SiteController extends Controller
{
    /**
     * @return RedirectResponse
     */
    public function indexAction()
    {
        $breadcrumbs = $this->get("white_october_breadcrumbs");
        $breadcrumbs->addRouteItem("Home", "veta_homework_homepage");


        return $this->render('VetaHomeworkBundle:Site:index.html.twig');
    }
}
