<?php

namespace Veta\HomeworkBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Veta\HomeworkBundle\Entity\Tag;
use Veta\HomeworkBundle\Entity\Translation\TagTranslation;

class LoadTagData extends AbstractFixture implements OrderedFixtureInterface
{

    /**
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        $tag_1 = new Tag();
        $tag_1->setTitle('citrus');
        $tag_1->addTranslation(new TagTranslation('uk', 'title', 'цитрус'));

        $manager->persist($tag_1);
        $this->addReference("tag_1", $tag_1);

        $tag_2 = new Tag();
        $tag_2->setTitle('sweet');
        $tag_2->addTranslation(new TagTranslation('uk', 'title', 'солодкий'));

        $manager->persist($tag_2);
        $this->addReference("tag_2", $tag_2);

        $tag_3 = new Tag();
        $tag_3->setTitle('tart');
        $tag_3->addTranslation(new TagTranslation('uk', 'title', 'терпкий'));

        $manager->persist($tag_3);
        $this->addReference("tag_3", $tag_3);

        $tag_4 = new Tag();
        $tag_4->setTitle('fresh');
        $tag_4->addTranslation(new TagTranslation('uk', 'title', 'свіжий'));

        $manager->persist($tag_4);
        $this->addReference("tag_4", $tag_4);

        $tag_5 = new Tag();
        $tag_5->setTitle('rose');
        $tag_5->addTranslation(new TagTranslation('uk', 'title', 'цитрус'));

        $manager->persist($tag_5);
        $this->addReference("tag_5", $tag_5);

        $tag_6 = new Tag();
        $tag_6->setTitle('jasmine');
        $tag_6->addTranslation(new TagTranslation('uk', 'title', 'солодкий'));

        $manager->persist($tag_6);
        $this->addReference("tag_6", $tag_6);

        $tag_7 = new Tag();
        $tag_7->setTitle('musk');
        $tag_7->addTranslation(new TagTranslation('uk', 'title', 'терпкий'));

        $manager->persist($tag_7);
        $this->addReference("tag_7", $tag_7);

        $tag_8 = new Tag();
        $tag_8->setTitle('peach flower');
        $tag_8->addTranslation(new TagTranslation('uk', 'title', 'персикова квітка'));

        $manager->persist($tag_8);
        $this->addReference("tag_8", $tag_8);

        $tag_9 = new Tag();
        $tag_9->setTitle('green tea');
        $tag_9->addTranslation(new TagTranslation('uk', 'title', 'зелений чай'));

        $manager->persist($tag_9);
        $this->addReference("tag_9", $tag_9);

        $tag_10 = new Tag();
        $tag_10->setTitle('lavender');
        $tag_10->addTranslation(new TagTranslation('uk', 'title', 'лаванда'));

        $manager->persist($tag_10);
        $this->addReference("tag_10", $tag_10);

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
