<?php

namespace Veta\HomeworkBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Veta\HomeworkBundle\Entity\Tag;
use Faker\Factory;
use Veta\HomeworkBundle\Entity\Translation\TagTranslation;

class LoadTagData extends AbstractFixture implements OrderedFixtureInterface
{

    /**
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        $faker = Factory::create();
        for ($i = 1; $i <= 5; $i++) {
            $tag = new Tag();
            $title = $faker->unique()->jobTitle;
            $tag->setTitle($title . ' en');
            $tag->addTranslation(new TagTranslation('uk', 'title', $title . ' uk'));

            $manager->persist($tag);
            $this->getReference('post_'. rand(1, 10))->addTag($tag);
            $manager->flush();
        }
    }

    /**
     * @return integer
     */
    public function getOrder()
    {
        return 3;
    }
}
