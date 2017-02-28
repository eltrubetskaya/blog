<?php

namespace Api\BlogBundle\EventListener;

use JMS\Serializer\EventDispatcher\PreSerializeEvent;
use Veta\HomeworkBundle\Entity\Post;
use JMS\Serializer\EventDispatcher\EventSubscriberInterface;

class SerializerSubscriber implements EventSubscriberInterface
{
    /**
     * @inheritdoc
     */
    public static function getSubscribedEvents()
    {
        return [
            [
                'event' => 'serializer.pre_serialize',
                'class' => Post::class,
                'method' => 'onPrePostSerialize'
            ],
        ];
    }

    /**
     * @param PreSerializeEvent $event
     */
    public function onPrePostSerialize(PreSerializeEvent $event)
    {
        /** @var Post $post */
        $post = $event->getObject();
    }
}
