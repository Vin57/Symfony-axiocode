<?php
namespace App\Domain\Application\Builder;

use Symfony\Component\HttpFoundation\Request;

class RequestBuilder
{
    public function setParamToRequest(Request $request, string $p_name, $p_param): void {
        $request_params = array_merge(
            [
                'query' => $request->query->all(),
                'request' => $request->request->all(),
                'attributes' => $request->attributes->all(),
                'cookies' => $request->cookies->all(),
                'files' => $request->files->all(),
                'server' => $request->server->all(),
                'content' => $request->getContent()
            ],
            [$p_name => $p_param]
        );
        $request->initialize(
            $request_params['query'],
            $request_params['request'],
            $request_params['attributes'],
            $request_params['cookies'],
            $request_params['files'],
            $request_params['server'],
            $request_params['content']
        );
    }
}