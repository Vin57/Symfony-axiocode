<?php


namespace App\Domain\Product\EventListener;


use App\Domain\Application\EventListener\BaseSubscriber;
use App\Domain\Product\Service\PictureServiceInterface;
use App\Entity\Picture;
use Axiocode\ApiBundle\Event\Events;
use Axiocode\ApiBundle\Event\PostProcessEvent;
use Axiocode\ApiBundle\Event\PreProcessEvent;
use Axiocode\ApiBundle\Exception\ProcessorException;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

class PictureListener extends BaseSubscriber
{
    const KEY_DATA_DELETE = '_delete_';
    private array $holdReceivedData = [];
    private PictureServiceInterface $pictureService;

    public function __construct(EventDispatcherInterface $eventDispatcher, PictureServiceInterface $_pictureService)
    {
        parent::__construct($eventDispatcher);
        $this->pictureService = $_pictureService;
    }

    public static function getSubscribedEvents(): array
    {
        return [
            Picture::class . '_' . Events::UPDATE_ONE_PRE_PROCESS => 'onUpdateOnePreProcess',
            Picture::class . '_' . Events::UPDATE_ONE_POST_PROCESS => 'onUpdateOnePostProcess',
        ];
    }

    public function onUpdateOnePreProcess(PreProcessEvent $event, $eventName)
    {
        $currentReceivedData = $event->receivedData;
        $this->holdReceivedData[$currentReceivedData['id'] . self::KEY_DATA_DELETE] = $currentReceivedData;
        if(is_bool(@$event->receivedData['is_main'])) {
            // Api bundle is unable to manage is_main field. (property isMain does not exist)
            // We'll manage to update is main field on post process event
            unset($event->receivedData['is_main']);
        }
        parent::onPreProcess($event, $eventName);
    }

    /**
     * @throws ProcessorException
     */
    public function onUpdateOnePostProcess(PostProcessEvent $event, $eventName)
    {
        parent::onPostProcess($event, $eventName);
        /** @var Picture $picture */
        $picture = $event->object;
        $received_data = $this->holdReceivedData;
        if (is_bool(@$received_data[$picture->getId(). self::KEY_DATA_DELETE]['is_main'])) {
            $this->updateIsMain($picture,  @$received_data[$picture->getId(). self::KEY_DATA_DELETE]['is_main']);
        }
    }

    /**
     * @throws ProcessorException
     */
    public function updateIsMain(Picture $picture, bool $is_main) {
        if (!$is_main) {
            if ($picture->getIsMain()) {
                throw new ProcessorException(["A product should have one main picture. You can't set the main picture to false. Try to set a new main picture."]);
            }
            return; // Else, we're trying to set 'false' on an already false 'is_main' property. It's unnecessary to go further
        }
        // Set a new main picture
        $this->pictureService->setAsMainPicture($picture);
    }
}