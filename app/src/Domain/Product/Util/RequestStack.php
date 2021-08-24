<?php


namespace App\Domain\Product\Util;

use Symfony\Component\HttpFoundation\Request;

/**
 * Request stack to manage request event lifecycle.
 * While an event is managed, holding the request so
 * it can be reread later.
 * Class RequestStack
 * @package App\Domain\Product\Util
 */
class RequestStack
{
    /**
     * @var Request[]
     */
    private array $requestStack;

    /**
     * @param Request $request
     * @param string|null $key A unique key to identify a request over the stack.
     * Default is to use request route name.
     */
    public function add(Request $request, ?string $key = null): void
    {
        $key ??= $request->get('_route');
        $this->requestStack[$key] = $request;
    }

    /**
     * Remove and return request matching given {@see $key}.
     * @param string|null $key
     * @return Request|null
     */
    public function remove(string $key = null): ?Request {
        if ($req = @$this->requestStack[$key]){
            unset($this->requestStack[$key]);
        }
        return $req;
    }

    /**
     * Indicate whether or not the stack contain an element for
     * {@see $key}.
     * @param string|null $key
     * @return bool
     */
    public function has(string $key = null): bool {
        return (bool)@$this->requestStack[$key];
    }
}