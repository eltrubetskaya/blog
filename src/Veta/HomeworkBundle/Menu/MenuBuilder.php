<?php

namespace Veta\HomeworkBundle\Menu;

use Knp\Menu\FactoryInterface;
use Knp\Menu\ItemInterface;

class MenuBuilder
{
    private $factory;

    /**
     * MenuBuilder constructor.
     * @param FactoryInterface $factory
     */
    public function __construct(FactoryInterface $factory)
    {
        $this->factory = $factory;
    }

    /**
     * @param array $options
     * @return ItemInterface
     */
    public function createMainMenu(array $options)
    {
        $menu = $this->factory->createItem('root');

        $menu->addChild('Home', ['route' => 'veta_homework_homepage']);
        $menu->addChild('Category', [
            'route' => 'veta_homework_category_index',
            'extras' => [
                'routes' => [

                ],
            ],
        ]);
        $menu->addChild('Post', [
            'route' => 'veta_homework_post_index',
            'extras' => [
                'routes' => [

                ],
            ],
        ]);

        return $menu;
    }

    /**
     * @param array $options
     * @return ItemInterface
     */
    public function createSidebarMenu(array $options)
    {
        $menu = $this->factory->createItem('sidebar');

        if (isset($options['include_homepage']) && $options['include_homepage']) {
            $menu->addChild('Home', ['route' => 'veta_homework_homepage']);
        }

        // ... add more children

        return $menu;
    }
}
