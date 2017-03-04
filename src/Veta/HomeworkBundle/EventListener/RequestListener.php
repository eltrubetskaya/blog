<?php

namespace Veta\HomeworkBundle\EventListener;

use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Form\Extension\Core\Type\SearchType;
use Symfony\Component\Form\FormFactory;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\Routing\Router;

class RequestListener
{
    /**
     * @var \Twig_Environment
     */
    protected $twig;

    /**
     * @var FormFactory
     */
    protected $form;

    /**
     * @var Router
     */
    protected $router;

    /**
     * @var ObjectManager
     */
    private $manager;

    /**
     * @var ContainerInterface
     */
    private $container;
    /**
     * ResponseListener constructor.
     * @param \Twig_Environment $twig
     * @param FormFactory $formFactory
     * @param Router $router
     * @param ObjectManager $manager
     */
    public function __construct(\Twig_Environment $twig, FormFactory $formFactory, Router $router, ObjectManager $manager, ContainerInterface $container = null)
    {
        $this->form = $formFactory;
        $this->twig = $twig;
        $this->router = $router;
        $this->manager = $manager;
        $this->container = $container;
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
        ])
        ;
        $this->twig->addGlobal('form_search', $form->createView());

        $postsSidebarModule = $this->manager->getRepository('VetaHomeworkBundle:Post')->findMostRecent($this->container->getParameter('veta_homework.sidebar.posts_limit'));
        $tagsSidebarModule = $this->manager->getRepository('VetaHomeworkBundle:Tag')->findLimited($this->container->getParameter('veta_homework.sidebar.tags_limit'));
        $this->twig->addGlobal('postsSidebarModule', $postsSidebarModule);
        $this->twig->addGlobal('tagsSidebarModule', $tagsSidebarModule);
    }
}
