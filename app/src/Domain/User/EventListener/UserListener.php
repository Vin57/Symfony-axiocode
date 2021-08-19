<?php

use Axiocode\ApiBundle\Event\Events;
use Axiocode\ApiBundle\Event\PostProcessEvent;
use Axiocode\ApiBundle\Event\PreProcessEvent;
use Axiocode\ApiBundle\Model\Hook;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class UserListener implements EventSubscriberInterface
{
    private UserBuilder $userBuilder;
    private ?string $hashedPassword = null;
    private EventDispatcher $eventDispatcher;

    public function __construct(UserBuilder $userBuilder, EventDispatcher $eventDispatcher) {
        $this->userBuilder = $userBuilder;
        $this->eventDispatcher = $eventDispatcher;
    }

    public static function getSubscribedEvents(): array
    {
        return [
            // Just after receivedData parsing
            'api_user_create' . '_' . Hook::PRE_PROCESS => 'onPreProcess',
            // Just before persisting data
            'api_user_create' . '_' . Hook::POST_PROCESS => 'onPostProcess',
        ];
    }

    public function onPreProcess(PreProcessEvent $event) {
        dd($event);
        list('login'=>$login, 'email'=>$email, 'password'=>$password) = $event->receivedData;
        $user = $this->userBuilder->withHashedPassword()->build($login, $email, $password);
        $this->hashedPassword = $user->getPassword();
        $this->eventDispatcher->dispatch($event, Events::CREATE_ONE_PRE_PROCESS);
    }

    public function onPostProcess(PostProcessEvent $event) {
        dd($event);
       $this->eventDispatcher->dispatch($event, Events::CREATE_ONE_POST_PROCESS);
       $event->object->setPassword($this->hashedPassword);
    }
}