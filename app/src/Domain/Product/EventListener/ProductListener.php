<?php

namespace App\Domain\Product\EventListener;

use App\Domain\Application\EventListener\BaseSubscriber;
use App\Domain\Application\Util\RequestJsonParser;
use App\Domain\Product\Factory\PictureFactory;
use App\Domain\Product\Util\ProductRequestFormDataParser;
use App\Domain\Product\Util\RequestStack;
use App\Entity\Picture;
use App\Entity\Product;
use Axiocode\ApiBundle\Controller\CreateOneApiController;
use Axiocode\ApiBundle\Controller\UpdateOneApiController;
use Axiocode\ApiBundle\Event\Events;
use Axiocode\ApiBundle\Event\PostProcessEvent;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Event\ControllerEvent;
use Symfony\Component\HttpKernel\KernelEvents;

class ProductListener extends BaseSubscriber
{
    const KEY_STACK_CREATE = '_create_';
    const KEY_STACK_UPDATE = '_update_';

    /**
     * @var RequestJsonParser
     */
    private RequestJsonParser $requestJsonParser;
    /**
     * @var ProductRequestFormDataParser
     */
    private ProductRequestFormDataParser $productRequestFormDataParser;
    /**
     * @var RequestStack
     */
    private RequestStack $requestStack;

    public function __construct(
        EventDispatcherInterface $eventDispatcher,
        RequestJsonParser $requestJsonParser,
        ProductRequestFormDataParser $productRequestFormDataParser,
        RequestStack $requestStack
    )
    {
        parent::__construct($eventDispatcher);
        $this->requestJsonParser = $requestJsonParser;
        $this->productRequestFormDataParser = $productRequestFormDataParser;
        $this->requestStack = $requestStack;
    }

    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::CONTROLLER => 'onControllerRequest',
            Product::class . '_' . Events::CREATE_ONE_POST_PROCESS => 'createOnePostProcess',
            Product::class . '_' . Events::CREATE_ONE_PRE_PROCESS => 'onPreProcess'
        ];
    }

    public function onControllerRequest(ControllerEvent $event)
    {
        // Treat request before Api bundle receive it
        $product_name = $event->getRequest()->request->get('name');
        $apiController = false;
        switch (true) {
            case !empty(array_filter((array)$event->getController(), fn($c) => $c instanceof CreateOneApiController)):
                $this->requestStack->add($event->getRequest(), $product_name . self::KEY_STACK_CREATE);
                $apiController = true;
                break;
            case !empty(array_filter((array)$event->getController(), fn($c) => $c instanceof UpdateOneApiController)):
                $this->requestStack->add($event->getRequest(), $product_name . self::KEY_STACK_UPDATE);
                $apiController = true;
                break;
        }
        if ($apiController && $event->getRequest()->getContentType() != 'json') {
            // Then jsonify the request content, as Api Bundle only treat content as json data
            $this->productRequestFormDataParser->parse($event->getRequest());
            $this->requestJsonParser->parseRequestContentToJson($event);
        }
    }

    public function createOnePostProcess(PostProcessEvent $event, $eventName)
    {
        /** @var Request|null $request */
        if ($request = $this->requestStack->remove($event->object->getName() . self::KEY_STACK_CREATE)) {
            $this->updateProductPostProcess($event, $eventName, $request);
        }
        parent::onPostProcess($event, $eventName);
    }

    public function updateOnePostProcess(PostProcessEvent $event, $eventName)
    {
        /** @var Request|null $request */
        if ($request = $this->requestStack->remove($event->object->getName() . self::KEY_STACK_UPDATE)) {
            $this->updateProductPostProcess($event, $eventName, $request);
        }
        parent::onPostProcess($event, $eventName);
    }

    private function updateProductPostProcess(PostProcessEvent $event, $eventName, ?Request $request)
    {
        /** @var Product $product */
        $product = $event->object;
        if ($request && $product) {
            $this->uploadProductPictures($request->files->all(), $product);
        }
    }

    /**
     * @param UploadedFile[] $files
     * @param Product $product
     */
    private function uploadProductPictures(array $files, Product $product)
    {
        /** @var Picture[] $pictures */
        array_map(
            function ($file, $name) use ($product) {
                $product->addPicture(PictureFactory::buildFromFile($file, $name, !$product->getPicture()));
            },
            $files,
            array_keys($files)
        );
    }
}