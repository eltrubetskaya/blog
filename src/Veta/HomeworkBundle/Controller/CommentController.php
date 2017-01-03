<?php

namespace Veta\HomeworkBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class CommentController extends Controller
{
    public function indexAction()
    {
        return $this->render('VetaHomeworkBundle:Comment:index.html.twig', array(
            // ...
        ));
    }
}
