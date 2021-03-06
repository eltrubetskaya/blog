<?php
namespace Veta\HomeworkBundle\Controller;

use Knp\Component\Pager\Pagination\PaginationInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Routing\Annotation\Route;
use Veta\HomeworkBundle\Entity\Comment;
use Veta\HomeworkBundle\Entity\Post;
use Veta\HomeworkBundle\Form\Type\CommentType;

class PostController extends Controller
{
    /**
     * @Route("/{_locale}/post", name="index")
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

        if ((($request->query->get('sort_date') == null) && ($request->query->get('sort_title') == null)) || ($request->query->get('sort_date') == 'new')) {
            $query = $this->getDoctrine()->getRepository('VetaHomeworkBundle:Post')->findMostRecentQuery();
        } else {
            if ($request->query->get('sort_date') == 'old') {
                $query = $this->getDoctrine()->getRepository('VetaHomeworkBundle:Post')->findMostOldQuery();
            } else {
                if ($request->query->get('sort_title') == 'up') {
                    $query = $this->getDoctrine()->getRepository('VetaHomeworkBundle:Post')->findOrderByTitleUp();
                } else {
                    $query = $this->getDoctrine()->getRepository('VetaHomeworkBundle:Post')->findOrderByTitleDown();
                }
            }
        }

        $posts = $this->sortPost($request, $query);
        return $this->render('VetaHomeworkBundle:Post:index.html.twig', [
            'posts' => $posts,

        ]);
    }

    /**
     * @Route("/post/like", name="likes")
     * @Method("POST")
     *
     * @param Request $request
     * @return Response
     */
    public function likesAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $post = $em->getRepository('VetaHomeworkBundle:Post')->find($request->request->get('id'));

        if ($request->getSession()->get($post->getSlug())) {
            $likes = $post->getLikes() - 1;
            $post->setLikes($likes);
            $em->flush();
            $request->getSession()->remove($post->getSlug());
        } else {
            $session = new Session();
            $likes = $post->getLikes() + 1;
            $post->setLikes($likes);
            $em->flush();
            $session->set($post->getSlug(), $post->getLikes());
        }

        return new Response();
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
     * @Route("/{_locale}/post/{slug}", name="views", requirements={"slug": "[\w\-]+"})
     * @Method("GET")
     *
     * @param Post $post
     * @return Response
     */
    public function viewAction(Post $post)
    {
        $breadcrumbs = $this->get("white_october_breadcrumbs");
        $breadcrumbs->addRouteItem("Home", "veta_homework_homepage");

        $breadcrumbs->addRouteItem($post->getCategory()->getTitle(), "veta_homework_category_index", ['slug' => $post->getCategory()->getSlug()]);
        $breadcrumbs->addRouteItem("Post", "veta_homework_post_index");
        $breadcrumbs->addRouteItem($post->getTitle(), "veta_homework_post_view", ['slug' => $post->getSlug()]);

        $comment = new Comment();
        $comment->setPost($post);

        $form = $this->createForm(CommentType::class, $comment, [
            'action' => $this->generateUrl('veta_homework_comment_create'),
            'method' => 'POST',
        ]);

        return $this->render('VetaHomeworkBundle:Post:view.html.twig', [
            'form' => $form->createView(),
            'post' => $post,
        ]);
    }

    /**
     * @Route("/{_locale}/post/search", name="search")
     * @Method("GET")
     *
     * @param Request $request
     * @return Response
     */
    public function searchAction(Request $request)
    {
        $q = $request->query->get('q');

        $finder = $this->container->get('fos_elastica.finder.blog.post');
        $query = $finder->createPaginatorAdapter($q);

        if (!$query) {
            $this->addFlash('search', "Search for text: \"$q\" no posts ... ");
        }

        $posts = $this->sortPost($request, $query);
        return $this->render('VetaHomeworkBundle:Post:search.html.twig', [
            'posts' => $posts,
        ]);
    }
}
