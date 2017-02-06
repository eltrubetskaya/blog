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
        $posts = $this->getDoctrine()->getRepository('VetaHomeworkBundle:Post')->findMostRecent($this->getParameter('veta_homework.homepage.posts_per_page'));
        $postsSidebarModule = $this->getDoctrine()->getRepository('VetaHomeworkBundle:Post')->findMostRecent($this->getParameter('veta_homework.sidebar.posts_limit'));
        $tagsSidebarModule = $this->getDoctrine()->getRepository('VetaHomeworkBundle:Tag')->findLimited($this->getParameter('veta_homework.sidebar.tags_limit'));
        return $this->render('VetaHomeworkBundle:Site:index.html.twig', [
            'posts' => $posts,
            'postsSidebarModule' => $postsSidebarModule,
            'tagsSidebarModule' => $tagsSidebarModule,

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
