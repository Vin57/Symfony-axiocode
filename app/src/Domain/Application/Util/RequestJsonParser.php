<?php
namespace App\Domain\Application\Util;
use App\Domain\Application\Builder\RequestBuilder;
use App\Domain\Product\Factory\PictureFactory;
use App\Entity\Picture;
use Symfony\Component\HttpFoundation\FileBag;
use Symfony\Component\HttpKernel\Event\ControllerEvent;
use Vich\UploaderBundle\VichUploaderBundle;

class RequestJsonParser
{
    private RequestBuilder $requestBuilder;

    public function __construct(RequestBuilder $requestBuilder)
    {
        $this->requestBuilder = $requestBuilder;
    }

    public function parseRequestContentToJson(ControllerEvent $event): void
    {
        $raw_content = json_encode($event->getRequest()->request->all());
        // Add json content as ApiBundle only treat raw content request.
        $this->requestBuilder->setParamToRequest($event->getRequest(), 'content', $raw_content);
        $event->getRequest()->headers->set('CONTENT_TYPE', 'application/json');
    }
}