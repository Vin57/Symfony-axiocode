<?php


namespace App\Domain\Product\Service;


use App\Domain\Application\Util\RequestJsonParser;
use App\Domain\Product\Util\RequestParserInterface;
use App\Domain\Product\Util\RequestStack;
use Axiocode\ApiBundle\Controller\CreateOneApiController;
use Axiocode\ApiBundle\Controller\UpdateOneApiController;
use Symfony\Component\HttpFoundation\Request;

class HandleMultiPartFormDataRequest
{
    const KEY_STACK_CREATE = '_create_';
    const KEY_STACK_UPDATE = '_update_';

    /**
     * @var RequestJsonParser
     */
    private RequestJsonParser $requestJsonParser;
    /**
     * @var RequestParserInterface
     */
    private RequestParserInterface $requestParser;
    /**
     * @var RequestStack
     */
    private RequestStack $requestStack;

    public function __construct(
        RequestJsonParser $requestJsonParser,
        RequestParserInterface $requestParser,
        RequestStack $requestStack
    )
    {
        $this->requestJsonParser = $requestJsonParser;
        $this->requestParser = $requestParser;
        $this->requestStack = $requestStack;
    }

    public function handle(Request $request) {
        // Treat request before Api bundle receive it
        /*$product_name = $request->request->get('name');

        switch (true) {
            case !empty(array_filter((array)$event->getController(), fn($c) => $c instanceof CreateOneApiController)):
                $this->requestStack->add($event->getRequest(), $product_name . self::KEY_STACK_CREATE);
                $apiController = true;
                break;
            case !empty(array_filter((array)$event->getController(), fn($c) => $c instanceof UpdateOneApiController)):
                $this->requestStack->add($event->getRequest(), $product_name . self::KEY_STACK_UPDATE);
                $apiController = true;
                break;
        }*/
        if ($request->getContentType() != 'json') {
            // Then jsonify the request content, as Api Bundle only treat content as json data
            $this->requestParser->parse($request);
            $this->requestJsonParser->parseRequestContentToJson($request);
        }
    }

    public abstract function isHandle(string $request_route);
}