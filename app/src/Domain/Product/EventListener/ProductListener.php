<?php
namespace App\Domain\Product\EventListener;

use App\Domain\Application\EventListener\BaseSubscriber;
use App\Entity\Product;
use Axiocode\ApiBundle\Event\Events;
use Axiocode\ApiBundle\Event\PostProcessEvent;

class ProductListener extends BaseSubscriber
{
    public static function getSubscribedEvents(): array
    {
        return [
            Product::class . '_' . Events::FETCH_ONE_POST_PROCESS => 'onPostProcess'
        ];
    }

    public function onPostProcess(PostProcessEvent $event, $eventName) {
        parent::onPostProcess($event, $eventName);
    }

}