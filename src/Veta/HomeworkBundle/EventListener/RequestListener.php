<?php

namespace Veta\HomeworkBundle\EventListener;

use Symfony\Component\Form\Extension\Core\Type\SearchType;
use Symfony\Component\Form\FormFactory;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\Routing\Router;

class RequestListener
{
    protected $twig;
    protected $form;
    protected $router;

    /**
     * ResponseListener constructor.
     * @param \Twig_Environment $twig
     * @param FormFactory $formFactory
     * @param Router $router
     */
    public function __construct(\Twig_Environment $twig, FormFactory $formFactory, Router $router)
    {
        $this->form = $formFactory;
        $this->twig = $twig;
        $this->router = $router;
    }

    /**
     * @param GetResponseEvent $event
     */
    public function onKernelRequest(GetResponseEvent $event)
    {
        if (!$event->isMasterRequest()) {
            return;
        }
        $form = $this->form->createNamed('q', SearchType::class, [], [
            'method' => 'get',
            'action' => $this->router->generate('veta_homework_post_search'),
            'attr' => [
                'class' =>  'navbar-form navbar-right',
            ]
        ])
        ;
        $this->twig->addGlobal('form_search', $form->createView());
    }
}
