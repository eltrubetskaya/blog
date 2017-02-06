<?php

namespace Veta\HomeworkBundle\EventListener;

use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Presta\SitemapBundle\Event\SitemapPopulateEvent;
use Presta\SitemapBundle\Sitemap\Url\UrlConcrete;

class SitemapSubscriber implements EventSubscriberInterface
{
    /**
     * @var UrlGeneratorInterface
     */
    private $urlGenerator;

    /**
     * @var ObjectManager
     */
    private $manager;

    /**
     * @param UrlGeneratorInterface $urlGenerator
     * @param ObjectManager         $manager
     */
    public function __construct(UrlGeneratorInterface $urlGenerator, ObjectManager $manager)
    {
        $this->urlGenerator = $urlGenerator;
        $this->manager = $manager;
    }

    /**
     * @inheritdoc
     */
    public static function getSubscribedEvents()
    {
        return [
            SitemapPopulateEvent::ON_SITEMAP_POPULATE => 'registerPostsPages',
        ];
    }

    /**
     * @param SitemapPopulateEvent $event
     */
    public function registerPostsPages(SitemapPopulateEvent $event)
    {
        $posts = $this->manager->getRepository('VetaHomeworkBundle:Post')->findAll();
        $categories = $this->manager->getRepository('VetaHomeworkBundle:Category')->findAll();
        foreach ($posts as $post) {
            $event->getUrlContainer()->addUrl(
                new UrlConcrete(
                    $this->urlGenerator->generate(
                        'veta_homework_post_view',
                        [
                            'slug' => $post->getSlug(),
                            '_locale' => 'en',

                        ],
                        UrlGeneratorInterface::ABSOLUTE_URL
                    )
                ),
                'posts'
            );
        }
        foreach ($categories as $category) {
            $event->getUrlContainer()->addUrl(
                new UrlConcrete(
                    $this->urlGenerator->generate(
                        'veta_homework_category_index',
                        [
                            'slug' => $category->getSlug(),
                            '_locale' => 'en',

                        ],
                        UrlGeneratorInterface::ABSOLUTE_URL
                    )
                ),
                'categories'
            );
        }
    }
}