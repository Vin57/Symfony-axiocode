<?php

namespace App\Domain\User\EventListener;

use App\Domain\Application\EventListener\BaseSubscriber;
use App\Entity\User;
use Axiocode\ApiBundle\Event\Events;
use Axiocode\ApiBundle\Event\PostProcessEvent;
use Axiocode\ApiBundle\Event\PreProcessEvent;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use App\Domain\User\Factory\UserBuilder;

class UserListener extends BaseSubscriber
{
    private UserBuilder $userBuilder;
    private ?string $hashedPassword = null;

    public function __construct(UserBuilder $userBuilder, EventDispatcherInterface $eventDispatcher) {
        parent::__construct($eventDispatcher);
        $this->userBuilder = $userBuilder;
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

    public function onPreProcess(PreProcessEvent $event, $eventName) {
        parent::onPreProcess($event, $eventName);
        list('login'=>$login, 'email'=>$email, 'password'=>$password) = $event->receivedData;
        $user = $this->userBuilder->withHashedPassword()->build($login, $email, $password);
        $this->hashedPassword = $user->getPassword();
    }

    public function onPostProcess(PostProcessEvent $event, $eventName) {
        parent::onPostProcess($event, $eventName);
        $event->object->setPassword($this->hashedPassword);
    }
}