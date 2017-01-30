<?php

namespace Veta\HomeworkBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Faker\Factory;
use Veta\HomeworkBundle\Entity\Comment;

class LoadCommentData extends AbstractFixture implements OrderedFixtureInterface
{

    /**
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        $faker = Factory::create();
        for ($i = 1; $i <= 5; $i++) {
            $comment = new Comment();
            $comment
                ->setPost($this->getReference('post_'. rand(1, 10)))
                ->setText($faker->text(200))
                ->setUser($this->getReference('user_'. rand(1, 5)))

            ;
            $manager->persist($comment);
        }

        $manager->flush();
    }

    /**
     * @return integer
     */
    public function getOrder()
    {
        return 5;
    }
}
