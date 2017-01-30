<?php

namespace Veta\HomeworkBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Faker\Factory;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Veta\HomeworkBundle\Entity\User;

class LoadUserData extends AbstractFixture implements OrderedFixtureInterface, ContainerAwareInterface
{
    /**
     * @var ContainerInterface
     */
    private $container;

    /**
     * @param ContainerInterface|null $container
     */
    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }

    /**
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        $encoder = $this->container->get('security.password_encoder');

        $faker = Factory::create();
        foreach (range(1, 5) as $index) {
            $user = new User();
            $password = $encoder->encodePassword($user, 'secret');
            $user
                ->setUsername($faker->userName)
                ->setPassword($password)
                ->setEmail($faker->email)
                ->setEnabled(true)
                ->setSalt(md5(uniqid()))
            ;

            $manager->persist($user);
            $this->addReference("user_{$index}", $user);
        }

        $manager->flush();
    }

    /**
     * @return integer
     */
    public function getOrder()
    {
        return 4;
    }
}
