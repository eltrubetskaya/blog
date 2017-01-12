<?php

namespace Veta\HomeworkBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Faker\Factory;
use Veta\HomeworkBundle\Entity\Post;

class LoadPostData extends AbstractFixture implements OrderedFixtureInterface
{

    /**
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        $faker = Factory::create();
        for ($i = 1; $i <= 10; $i++) {
            $post = new Post();
            $post
                ->setTitle($faker->unique()->name)
                ->setCategory($this->getReference('category_'. rand(1, 5)))
                ->setDescription($faker->text(100))
                ->setText($faker->text(300))
                ->setStatus(true)
                ->setDateCreate($faker->dateTime)
                ->setLikes($faker->numberBetween(5, 25))
            ;

            $manager->persist($post);
            $this->addReference("post_{$i}", $post);
        }

        $manager->flush();
    }

    /**
     * @return integer
     */
    public function getOrder()
    {
        return 2;
    }
}
