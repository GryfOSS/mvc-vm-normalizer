<?php

namespace Praetorian\Mvc\Subscriber;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\KernelEvent;
use Symfony\Component\HttpKernel\Event\ViewEvent;
use Symfony\Component\HttpKernel\KernelEvents;

class ViewSubscriber implements EventSubscriberInterface
{
    public static function getSubscribedEvents()
    {
        return [
            'kernel.view' => 'normalize'
        ];
    }

    public function normalize(ViewEvent $event)
    {
        $object = $event->getControllerResult(); //actual object
        if (!is_object($object)) {
            return false;
        }

        //TODO add something in the process as reflection is heavy
        $reflector = new \ReflectionClass($object::class);
        $attributes = $reflector->getAttributes(DefaultViewModel::class);

        if (empty($attributes)) {
            return false;
        }

        $attributes = $reflector->getAttributes(DefaultViewModel::class);
        $attrInstance = $attributes[0]->newInstance()->getViewModelClass();
        $event->setControllerResult(new $attrInstance($object));
    }
}