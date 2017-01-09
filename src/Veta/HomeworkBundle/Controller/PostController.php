<?php

namespace Veta\HomeworkBundle\Controller;

use Knp\Component\Pager\Pagination\PaginationInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Veta\HomeworkBundle\Entity\Comment;
use Veta\HomeworkBundle\Form\Type\CommentType;

class PostController extends Controller
{
    /**
     * Show all Posts
     *
     * @Route("/post", name="index")
     * @Method("GET")
     *
     * @param Request $request
     * @return Response
     */
    public function indexAction(Request $request)
    {
        $breadcrumbs = $this->get("white_october_breadcrumbs");
        $breadcrumbs->addRouteItem("Home", "veta_homework_homepage");
        $breadcrumbs->addRouteItem("Post", "veta_homework_post_index");

        $em = $this->getDoctrine()->getManager();
        if ((($request->query->get('sort_date') == null) && ($request->query->get('sort_title') == null)) || ($request->query->get('sort_date') == 'new')) {
            $query = $em->getRepository('VetaHomeworkBundle:Post')->findMostRecentQuery();
        } else {
            if ($request->query->get('sort_date') == 'old') {
                $query = $em->getRepository('VetaHomeworkBundle:Post')->findMostOldQuery();
            } else {
                if ($request->query->get('sort_title') == 'up') {
                    $query = $em->getRepository('VetaHomeworkBundle:Post')->findOrderByTitleUp();
                } else {
                    $query = $em->getRepository('VetaHomeworkBundle:Post')->findOrderByTitleDown();
                }
            }
        }

        $posts = $this->sortPost($request, $query);
        return $this->render('VetaHomeworkBundle:Post:index.html.twig', [
            'posts' => $posts,

        ]);
    }

    /**
     * @param $request
     * @return PaginationInterface
     */
    private function sortPost($request, $query)
    {
        $paginator  = $this->get('knp_paginator');
        if ($request->query->get('count') != null) {
            $count = $request->query->get('count');
        } else {
            $count = 5;
        }
        return $paginator->paginate($query, $request->query->getInt('page', 1), $count);
    }

    /**
     * View data Post
     *
     * @Route("/post/{slug}", name="views", requirements={"slug": "[\w\-]+"})
     * @Method("GET")
     *
     * @param Request $request
     * @return Response
     */
    public function viewAction(Request $request)
    {
        $breadcrumbs = $this->get("white_october_breadcrumbs");
        $breadcrumbs->addRouteItem("Home", "veta_homework_homepage");


        $slug = $request->attributes->get('slug');
        $em = $this->getDoctrine()->getManager();
        $post = $em->getRepository('VetaHomeworkBundle:Post')->findOneBy(['slug' => $slug]);

        $breadcrumbs->addRouteItem($post->getCategory()->getTitle(), "veta_homework_category_index", ['slug' => $post->getCategory()->getSlug()]);
        $breadcrumbs->addRouteItem("Post", "veta_homework_post_index");
        $breadcrumbs->addRouteItem($post->getTitle(), "veta_homework_post_view", ['slug' => $post->getSlug()]);

        $all_posts = $em->getRepository('VetaHomeworkBundle:Post')->findMostRecent();

        $comment = new Comment();
        $comment->setPost($post);
        $comment->setDateCreate(new \DateTime());

        $form = $this->createForm(CommentType::class, $comment, [
            'action' => $this->generateUrl('veta_homework_comment_create'),
            'method' => 'POST',
        ])
        ;

        return $this->render('VetaHomeworkBundle:Post:view.html.twig', [
            'form' => $form->createView(),
            'post' => $post,
            'all_posts' => $all_posts,

        ]);
    }

    /**
     * Search data Posts
     *
     * @Route("/post/search", name="search")
     * @Method("GET")
     *
     * @param Request $request
     * @return Response
     */
    public function searchAction(Request $request)
    {
        $q = $request->query->get('q');
        $search = explode(" ", $q);
        $em = $this->getDoctrine()->getManager();
        $query = $em->getRepository('VetaHomeworkBundle:Post')->findQ($q, $search, 10);
        if (!$query) {
            $this->addFlash('search', "Search for text: \"$q\" no posts ... ");
        }
        $all_posts = $em->getRepository('VetaHomeworkBundle:Post')->findMostRecent();
        $posts = $this->sortPost($request, $query);
        return $this->render('VetaHomeworkBundle:Post:search.html.twig', [
            'posts' => $posts,
            'all_posts' => $all_posts,

        ]);
    }
}
