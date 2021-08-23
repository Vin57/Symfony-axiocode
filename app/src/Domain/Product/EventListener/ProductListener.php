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
use Axiocode\ApiBundle\Event\Events;
use Axiocode\ApiBundle\Event\PostProcessEvent;
use http\Exception\InvalidArgumentException;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\InputBag;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Event\ControllerEvent;
use Symfony\Component\HttpKernel\KernelEvents;

class ProductListener extends BaseSubscriber
{
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

    public function __construct(EventDispatcherInterface $eventDispatcher, RequestJsonParser $requestJsonParser, ProductRequestFormDataParser $productRequestFormDataParser, RequestStack $requestStack)
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
            Product::class . '_' . Events::FETCH_ONE_POST_PROCESS => 'onPostProcess',
            Product::class . '_' . Events::CREATE_ONE_POST_PROCESS => 'onCreateOnePostProcess',
            Product::class . '_' . Events::CREATE_ONE_PRE_PROCESS => 'onPreProcess'
        ];
    }

    public function onControllerRequest(ControllerEvent $event) {
        if ($this->eventIsCreateOneProduct($event) && $event->getRequest()->getContentType() != 'json') {
            $this->requestStack->add($event->getRequest(), $product_name = $event->getRequest()->request->get('name'));
            $this->productRequestFormDataParser->parse($event->getRequest());
            $this->requestJsonParser->parseRequestContentToJson($event);
        }
    }

    public function onCreateOnePostProcess(PostProcessEvent $event, $eventName) {
        /** @var Product $product */
        $product = $event->object;
        if ($this->requestStack->has($event->object->getName())) {
            $request = $this->requestStack->remove($product->getName());
            /** @var Picture[] $pictures */
            $pictures = array_map(fn($file) => PictureFactory::buildFromFile($file), $request->files->all());
            array_map(fn($picture, $index) => $product->addPicture($picture->setIsMain($index === 0)), $pictures, array_keys($pictures));
        }

        parent::onPostProcess($event, $eventName);
    }

    private function eventIsCreateOneProduct(ControllerEvent $event): bool
    {
        return $event->getRequest()->get('_route') === 'api_product_create'
        && !empty(array_filter((array)$event->getController(), fn($c) => $c instanceof CreateOneApiController));
    }
}