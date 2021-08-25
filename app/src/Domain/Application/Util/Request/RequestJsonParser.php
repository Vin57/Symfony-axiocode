<?php
namespace App\Domain\Application\Util;
use App\Domain\Application\Builder\RequestBuilder;
use App\Domain\Product\Factory\PictureFactory;
use App\Entity\Picture;
use Symfony\Component\HttpFoundation\FileBag;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Event\ControllerEvent;
use Vich\UploaderBundle\VichUploaderBundle;

class RequestJsonParser
{
    private RequestBuilder $requestBuilder;

    public function __construct(RequestBuilder $requestBuilder)
    {
        $this->requestBuilder = $requestBuilder;
    }

    public function parseRequestContentToJson(Request $request): void
    {
        $raw_content = json_encode($request->request->all());
        // Add json content as ApiBundle only treat raw content request.
        $this->requestBuilder->setParamToRequest($request, 'content', $raw_content);
        $request->headers->set('CONTENT_TYPE', 'application/json');
    }
}