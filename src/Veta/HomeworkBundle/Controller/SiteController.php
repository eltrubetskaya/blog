<?php

namespace Veta\HomeworkBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
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

    /**
     * @param Request $request
     * @return RedirectResponse
     */
    public function setupAction(Request $request)
    {
        $locale = $request->getLocale();
        return $this->redirectToRoute('veta_homework_homepage', [
           '_locale' => $locale
        ]);
    }
}
