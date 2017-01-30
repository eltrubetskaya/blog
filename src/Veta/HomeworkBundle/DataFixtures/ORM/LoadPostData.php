<?php

namespace Veta\HomeworkBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Faker\Factory;
use Veta\HomeworkBundle\Entity\Post;
use Veta\HomeworkBundle\Entity\Translation\PostTranslation;

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
            $title = $faker->unique()->name;
            $description = $faker->text(100);
            $text = $faker->text(300);
            $post
                ->setTitle($title . ' en')
                ->addTranslation(new PostTranslation('uk', 'title', $title . ' uk'))
                ->setCategory($this->getReference('category_'. rand(1, 5)))
                ->setDescription($description . 'en')
                ->addTranslation(new PostTranslation('uk', 'description', $description . ' uk'))
                ->setText($text . 'en')
                ->addTranslation(new PostTranslation('uk', 'text', $text . ' uk'))
                ->setStatus(true)
                ->setDateCreate($faker->dateTime)
                ->setLikes($faker->numberBetween(5, 25))
                ->addTag($this->getReference('tag_'. rand(1, 10)))
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
        return 3;
    }
}
