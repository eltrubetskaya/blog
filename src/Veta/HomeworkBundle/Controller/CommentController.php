<?php

namespace Veta\HomeworkBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Veta\HomeworkBundle\Entity\Comment;
use Veta\HomeworkBundle\Form\Type\CommentType;

class CommentController extends Controller
{
    /**
     * @Route("/comment", name="create")
     * @Method("POST")
     *
     * @param Request $request
     * @return RedirectResponse|Response
     */
    public function createAction(Request $request)
    {
        $comment = new Comment();

        $form = $this->createForm(CommentType::class, $comment, [
            'action' => $this->generateUrl('veta_homework_comment_create'),
            'method' => 'POST',
        ]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $comment = $form->getData();
            $em = $this->getDoctrine()->getManager();
            $em->persist($comment);
            $em->flush();
            return $this->redirectToRoute('veta_homework_post_view', [
                'slug' => $comment->getPost()->getSlug(),

            ]);
        }

        $this->addFlash('info', 'Error Comment add');

        return $this->redirectToRoute('veta_homework_post_view', [
            'slug' => $comment->getPost()->getSlug(),

        ]);
    }
}
