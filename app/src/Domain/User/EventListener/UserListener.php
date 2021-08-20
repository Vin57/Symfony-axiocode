<?php

namespace App\Domain\User\EventListener;

use App\Entity\User;
use Axiocode\ApiBundle\Event\Events;
use Axiocode\ApiBundle\Event\PostProcessEvent;
use Axiocode\ApiBundle\Event\PreProcessEvent;
use Axiocode\ApiBundle\Model\Hook;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use App\Domain\User\Factory\UserBuilder;

class UserListener implements EventSubscriberInterface
{
    private UserBuilder $userBuilder;
    private ?string $hashedPassword = null;
    private EventDispatcherInterface $eventDispatcher;

    public function __construct(UserBuilder $userBuilder, EventDispatcherInterface $eventDispatcher) {
        $this->userBuilder = $userBuilder;
        $this->eventDispatcher = $eventDispatcher;
    }

    public static function getSubscribedEvents(): array
    {
        return [
            // Just after receivedData parsing
            User::class . '_' . Events::CREATE_ONE_PRE_PROCESS => 'onPreProcess',
            // Just before persisting data
            User::class . '_' . Events::CREATE_ONE_POST_PROCESS => 'onPostProcess',
        ];
    }

    public function onPreProcess(PreProcessEvent $event) {
        $this->eventDispatcher->dispatch($event, Events::CREATE_ONE_PRE_PROCESS);
        list('login'=>$login, 'email'=>$email, 'password'=>$password) = $event->receivedData;
        $user = $this->userBuilder->withHashedPassword()->build($login, $email, $password);
        $this->hashedPassword = $user->getPassword();
    }

    public function onPostProcess(PostProcessEvent $event) {
        $event->validationGroup = "Default";
        $this->eventDispatcher->dispatch($event, Events::CREATE_ONE_POST_PROCESS);
        $event->object->setPassword($this->hashedPassword);
    }
}