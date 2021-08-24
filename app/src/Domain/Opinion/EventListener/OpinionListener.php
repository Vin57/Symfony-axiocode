<?php
namespace App\Domain\Opinion\EventListener;

use App\Domain\Application\EventListener\BaseSubscriber;
use App\Entity\Opinion;
use Axiocode\ApiBundle\Event\Events;
use Axiocode\ApiBundle\Event\PostProcessEvent;
use Axiocode\ApiBundle\Event\PreProcessEvent;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\Security\Core\Security;

class OpinionListener extends BaseSubscriber
{
    private Security $security;

    public function __construct(EventDispatcherInterface $eventDispatcher, Security $security)
    {
        parent::__construct($eventDispatcher);
        $this->security = $security;
    }

    public static function getSubscribedEvents(): array
    {
        return [
            Opinion::class . '_' . Events::CREATE_ONE_PRE_PROCESS => 'onCreateOnePreProcess',
            Opinion::class . '_' . Events::DELETE_ONE_POST_PROCESS => 'onDeleteOnePostProcess',
            Opinion::class . '_' . Events::UPDATE_ONE_POST_PROCESS => 'onUpdateOnePostProcess',
            Opinion::class . '_' . Events::CREATE_ONE_POST_PROCESS => 'onPostProcess',
        ];
    }

    public function onCreateOnePreProcess(PreProcessEvent $event, $eventName) {
        parent::onPreProcess($event, $eventName);
        $event->receivedData['user'] = ['id' => $this->security->getUser()->getId()];
    }

    public function onPostProcess(PostProcessEvent $event, $eventName) {
        parent::onPostProcess($event, $eventName);
    }

    public function onUpdateOnePostProcess(PostProcessEvent $event, $eventName) {
        if (!$this->security->isGranted('POST_EDIT', $event->object)) {
            throw new AccessDeniedHttpException("Not granted");
        }
        parent::onPostProcess($event, $eventName);
    }

    public function onDeleteOnePostProcess(PostProcessEvent $event, $eventName) {
        if (!$this->security->isGranted('POST_DELETE', $event->object)) {
            throw new AccessDeniedHttpException("Not granted");
        }
        parent::onPostProcess($event, $eventName);
    }
}