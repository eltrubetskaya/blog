<?php

namespace Veta\HomeworkBundle\Menu;

use Knp\Menu\FactoryInterface;
use Knp\Menu\ItemInterface;
use Veta\HomeworkBundle\Entity\Category;
use Doctrine\ORM\EntityManager;

class MenuBuilder
{
    private $factory;
    private $em;
    private $current;

    /**
     * MenuBuilder constructor.
     * @param FactoryInterface $factory
     * @param EntityManager $em
     * @param bool $current
     */
    public function __construct(FactoryInterface $factory, EntityManager $em, $current = false)
    {
        $this->factory = $factory;
        $this->em = $em;
        $this->current = $current;
    }

    /**
     * @param array $options
     * @return ItemInterface
     */
    public function createMainMenu(array $options)
    {
        $category = $this->em->getRepository('VetaHomeworkBundle:Category');
        $nodes =  $category->getRootNodes();

        $menu = $this->factory->createItem('root');
        $menu->addChild('Home', ['route' => 'veta_homework_homepage']);

        foreach ($nodes as $node) {
            $nodesChildren =  $category->getChildren($node, false);
            $menu
                ->addChild($node->getTitle(), [
                    'route' => 'veta_homework_category_index',
                    'routeParameters' => ['slug' => $node->getSlug()],
                    'extras' => [
                        'routes' => [
                            [
                                'route' => 'veta_homework_category_index',
                                'routeParameters' => ['slug' => $this->isChild($nodesChildren, $options['slug'])],
                            ],
                            [
                                'route' => 'veta_homework_post_view',
                                'routeParameters' => ['slug' => $this->isCategoryPost($nodesChildren, $options['slug'])],
                            ],
                        ],
                    ],
                ])
            ;
            $menu[$node->getTitle()]->setCurrent($this->current);
        }
        return $menu;
    }

    /**
     * @param array $options
     * @return ItemInterface
     */
    public function createSidebarMenu(array $options)
    {
        $category = $this->em->getRepository('VetaHomeworkBundle:Category');
        $nodes =  $category->getRootNodes();

        $menu = $this->factory->createItem('root');
        $menu->setChildrenAttribute('class', 'nav-list');

        foreach ($nodes as $node) {
            $menu
                ->addChild($node->getTitle())
                ->setLabelAttributes([
                    'class' => 'tree-toggle nav-header',
                    'style' => 'font-weight: bold',
                ])
                ->setChildrenAttribute('class', 'nav nav-list tree')
            ;
            $nodesChildren =  $category->getChildren($node, false);
            foreach ($nodesChildren as $nodeChildren) {
                $menu[$node->getTitle()]
                    ->addChild($nodeChildren->getTitle(), [
                        'route' => 'veta_homework_category_index',
                        'routeParameters' => ['slug' => $nodeChildren->getSlug()],
                        'extras' => [
                            'routes' => [
                            ],
                        ],
                    ])
                ;
            }
        }
        return $menu;
    }

    /**
     * @param $nodesChildren
     * @param $slug
     * @return mixed
     */
    public function isCategoryPost($nodesChildren, $slug)
    {
        $post = $this->em->getRepository('VetaHomeworkBundle:Post')->findOneBy(['slug' => $slug]);
        foreach ($nodesChildren as $nodeChildren) {
            if ($post) {
                $category = $post->getCategory();
                if (($nodeChildren->getSlug() == $category->getSlug()) || ($nodeChildren->getParent()->getSlug() == $category->getSlug())) {
                    $this->current = true;
                    return $slug;
                } else {
                    $this->current = false;
                }
            }
        }
    }

    /**
     * @param $nodesChildren
     * @param $slug
     * @return mixed
     */
    public function isChild($nodesChildren, $slug)
    {
        foreach ($nodesChildren as $nodeChildren) {
            if (($nodeChildren->getSlug() == $slug) || ($nodeChildren->getParent()->getSlug() == $slug)) {
                $this->current = true;
                return $slug;
            } else {
                $this->current = false;
            }
        }
    }
}
