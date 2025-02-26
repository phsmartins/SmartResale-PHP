<?php

namespace Smart\Resale\Controller;

use League\Plates\Engine;
use Nyholm\Psr7\Response;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

readonly class Wip implements RequestHandlerInterface
{
    public function __construct(
        private Engine $engine
    ) {
    }

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        return new Response(
            200,
            body: $this->engine->render(
                'wip'
            )
        );
    }
}
