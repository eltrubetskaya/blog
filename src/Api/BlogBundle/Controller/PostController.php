<?php

namespace Api\BlogBundle\Controller;

use FOS\RestBundle\Controller\Annotations\Get;
use FOS\RestBundle\Controller\Annotations\QueryParam;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Request\ParamFetcher;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use FOS\RestBundle\Controller\Annotations\View as ViewAnnotation;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Veta\HomeworkBundle\Entity\Post;

class PostController extends FOSRestController
{
    /**
     * @ApiDoc(
     *     resource=true,
     *     resourceDescription="Operations on Post.",
     *     method="get",
     *     description="Retrieve Post list.",
     *     requirements={
     *          {
     *              "name"="_locale",
     *              "dataType"="string",
     *              "requirement"="uk|en",
     *              "default" ="en",
     *              "description"="Selects language of data you want to receive"
     *          }
     *      },
     *     statusCodes={
     *         200 = "Returned when all parameters were correct",
     *         400 = "Validation failed."
     *     },
     *     responseMap={
     *         200 = "Veta\HomeworkBundle\Entity\Post",
     *         400 = {
     *             "class"="CommonBundle\Model\ValidationErrors",
     *             "parsers"={"Nelmio\ApiDocBundle\Parser\JmsMetadataParser"}
     *         }
     *     },
     *     output={
     *          "class" = "Veta\HomeworkBundle\Entity\Post"
     *      }
     *  )
     * @Get("{_locale}/post", name="index", defaults={"_locale":"en"}, requirements={"_locale": "uk|en"})
     * @ViewAnnotation()
     * @return Response
     */
    public function getPostsAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $posts = $em->getRepository('VetaHomeworkBundle:Post')->findAll();
        foreach ($posts as $post) {
            $post->setLocale($request->get('_locale'));
            $em->refresh($post);

            $category = $em->getRepository('VetaHomeworkBundle:Category')->find($post->getCategory());
            $category->setLocale($request->get('_locale'));
            $em->refresh($category);

            if ($post->getTranslations()) {
                $post->unsetTranslations();
            }

            if ($category->getTranslations()) {
                $category->unsetTranslations();
            }
        }


        $view = $this->view($posts, 200);

        return $this->handleView($view);
    }

    /**
     * @ApiDoc(
     *     resource=false,
     *     resourceDescription="Operations on Post.",
     *     method="get",
     *     description="Retrieve view post.",
     *     requirements={
     *          {
     *              "name"="_locale",
     *              "dataType"="string",
     *              "requirement"="uk|en",
     *              "default" ="en",
     *              "description"="Selects language of data you want to receive"
     *          }
     *      },
     *     statusCodes={
     *         200 = "Returned when all parameters were correct",
     *         400 = "Validation failed."
     *     },
     *     responseMap={
     *         200 = "Veta\HomeworkBundle\Entity\Post",
     *         400 = {
     *             "class"="CommonBundle\Model\ValidationErrors",
     *             "parsers"={"Nelmio\ApiDocBundle\Parser\JmsMetadataParser"}
     *         }
     *     },
     *     output={
     *          "class" = "Veta\HomeworkBundle\Entity\Post"
     *      }
     *  )
     * @Get("{_locale}/post/{slug}", name="view", defaults={"_locale":"en"}, requirements={"slug": "[\w\-]+", "_locale": "uk|en"})
     * @ViewAnnotation()
     * @return Response
     */
    public function getPostAction(Request $request, Post $post)
    {
        $em = $this->getDoctrine()->getManager();
        $post->setLocale($request->get('_locale'));
        $em->refresh($post);

        $category = $em->getRepository('VetaHomeworkBundle:Category')->find($post->getCategory());
        $category->setLocale($request->get('_locale'));
        $em->refresh($category);

        if ($post->getTranslations()) {
            $post->unsetTranslations();
        }

        if ($category->getTranslations()) {
            $category->unsetTranslations();
        }

        $view = $this->view($post, 200);

        return $this->handleView($view);
    }
}
