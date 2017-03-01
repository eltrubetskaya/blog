<?php

namespace Api\BlogBundle\Controller;

use FOS\RestBundle\Controller\Annotations\Get;
use FOS\RestBundle\Controller\FOSRestController;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use FOS\RestBundle\Controller\Annotations\View as ViewAnnotation;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class CategoryController extends FOSRestController
{
    /**
     * @ApiDoc(
     *     resource=true,
     *     description="Returns a collection of Category",
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
     *         200 = "Veta\HomeworkBundle\Entity\Category",
     *         400 = {
     *             "class"="CommonBundle\Model\ValidationErrors",
     *             "parsers"={"Nelmio\ApiDocBundle\Parser\JmsMetadataParser"}
     *         }
     *     },
     *     output={
     *          "class" = "Veta\HomeworkBundle\Entity\Category"
     *      }
     *  )
     * @Get("{_locale}/category", defaults={"_locale":"en"}, requirements={"_locale": "uk|en"})
     * @ViewAnnotation()
     * @return Response
     */
    public function getCategoriesAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $categories = $em->getRepository('VetaHomeworkBundle:Category')->findAll();
        foreach ($categories as $category) {
            $category->setLocale($request->get('_locale'));
            $em->refresh($category);

            if ($category->getTranslations()) {
                $category->unsetTranslations();
            }
        }


        $view = $this->view($categories, 200);

        return $this->handleView($view);
    }
}
