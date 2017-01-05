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
    private $menuName;
    private $menu;

    /**
     * MenuBuilder constructor.
     * @param FactoryInterface $factory
     * @param EntityManager $em
     */
    public function __construct(FactoryInterface $factory, EntityManager $em)
    {
        $this->factory = $factory;
        $this->em = $em;
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
            $menu->addChild($node->getTitle(), [
                'route' => 'veta_homework_category_index',
                'routeParameters' => ['slug' => $node->getSlug()],
                'extras' => [
                    'routes' => [

                    ],
                ],
            ])
            ;
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
}
