<?php

namespace Veta\HomeworkBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Veta\HomeworkBundle\Entity\Category;
use Veta\HomeworkBundle\Entity\Translation\CategoryTranslation;

class LoadCategoryData extends AbstractFixture implements OrderedFixtureInterface
{

    /**
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        $parent = null;
        $category_1 = new Category();
        $category_1
            ->setTitle('Perfumes female')
            ->addTranslation(new CategoryTranslation('uk', 'title', 'Парфумерія жіноча'))
            ->setStatus(true)
            ->setParent($parent)
        ;
        $parent = $category_1;
        $manager->persist($category_1);
        $this->addReference("category_1", $category_1);

        $category_2 = new Category();
        $category_2
            ->setTitle('Cosmetics for eyes')
            ->addTranslation(new CategoryTranslation('uk', 'title', 'Косметика для очей'))
            ->setStatus(true)
            ->setParent($parent)
        ;
        $parent = $category_2;
        $manager->persist($category_2);
        $this->addReference("category_2", $category_2);


        $category_3 = new Category();
        $category_3
            ->setTitle('Perfumes male')
            ->addTranslation(new CategoryTranslation('uk', 'title', 'Парфумерія чоловіча'))
            ->setStatus(true)
            ->setParent($parent)
        ;
        $parent = null;
        $manager->persist($category_3);
        $this->addReference("category_3", $category_3);

        $category_4 = new Category();
        $category_4
            ->setTitle('Cosmetics for face')
            ->addTranslation(new CategoryTranslation('uk', 'title', 'Косметика для обличчя'))
            ->setStatus(true)
            ->setParent($parent)
        ;
        $parent = $category_4;
        $manager->persist($category_4);
        $this->addReference("category_4", $category_4);

        $category_5 = new Category();
        $category_5
            ->setTitle('Bath line')
            ->addTranslation(new CategoryTranslation('uk', 'title', 'Банна лінія'))
            ->setStatus(true)
            ->setParent($parent)
        ;
        $manager->persist($category_5);
        $this->addReference("category_5", $category_5);

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
