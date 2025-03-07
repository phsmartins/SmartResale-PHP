<?php

namespace Smart\Resale\Controller\User;

use League\Plates\Engine;
use Nyholm\Psr7\Response;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Smart\Resale\Repository\UserRepository;
use Smart\Resale\Traits\FlashMessageTrait;

readonly class UserConfigController implements RequestHandlerInterface
{
    use FlashMessageTrait;

    public function __construct(
        private Engine $engine,
        private UserRepository $userRepository,
    )
    {}

    public function handle(ServerRequestInterface $request): Response
    {
        $userData = $this->userRepository->findUserById($_SESSION['user_id']);

        if (!$userData) {
            $this->addErrorMessageAlert(
                'Você foi desconectado por um erro inesperado',
                'Tente logar novamente'
            );

            session_destroy();
            return new Response(302, ['Location' => '/login']);
        }

        return new Response(
            200,
            body: $this->engine->render(
                'user/config',
                ['userData' => $userData]
            )
        );
    }
}
