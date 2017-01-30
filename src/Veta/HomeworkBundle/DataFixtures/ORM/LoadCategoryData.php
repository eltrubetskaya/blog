<?php

namespace Veta\HomeworkBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Faker\Factory;
use Veta\HomeworkBundle\Entity\Category;
use Veta\HomeworkBundle\Entity\Translation\CategoryTranslation;

class LoadCategoryData extends AbstractFixture implements OrderedFixtureInterface
{

    /**
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        $faker = Factory::create();
        $parent = null;
        for ($i = 1; $i <= 5; $i++) {
            $category = new Category();
            $title = $faker->unique()->name;
            $category
                ->setTitle($title . ' en')
                ->addTranslation(new CategoryTranslation('uk', 'title', $title . ' uk'))
                ->setStatus(true)
                ->setParent($parent)
            ;
            if ($i === 3) {
                $parent = null;
            } else {
                $parent = $category;
            }
            $manager->persist($category);
            $this->addReference("category_{$i}", $category);
        }

        $manager->flush();
    }

    /**
     * @return int
     */
    public function getOrder()
    {
        return 1;
    }
}
