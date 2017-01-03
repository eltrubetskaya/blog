<?php

namespace Veta\HomeworkBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\RedirectResponse;

class CategoryController extends Controller
{
    /**
     * @return RedirectResponse
     */
    public function indexAction()
    {
        $breadcrumbs = $this->get("white_october_breadcrumbs");
        $breadcrumbs->addRouteItem("Home", "veta_homework_homepage");
        $breadcrumbs->addRouteItem("Category", "veta_homework_category_index");


        return $this->render('VetaHomeworkBundle:Category:index.html.twig');
    }
}
