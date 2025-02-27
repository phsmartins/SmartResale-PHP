<?php

namespace Smart\Resale\Controller\User;

use League\Plates\Engine;
use Nyholm\Psr7\Response;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

readonly class SignUpFormController implements RequestHandlerInterface
{
    public function __construct(
        private Engine $engine
    )
    {}

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        if (array_key_exists('logged_in', $_SESSION) && $_SESSION['logged_in'] === true) {
            return new Response(302, ['Location' => '/']);
        }

        return new Response(200, body: $this->engine->render('user/signup-form'));
    }
}
