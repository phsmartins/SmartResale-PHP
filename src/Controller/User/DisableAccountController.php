<?php

namespace Smart\Resale\Controller\User;

use Nyholm\Psr7\Response;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Smart\Resale\Repository\UserRepository;
use Smart\Resale\Traits\FlashMessageTrait;

readonly class DisableAccountController implements RequestHandlerInterface
{
    use FlashMessageTrait;

    public function __construct(
        private UserRepository $userRepository,
    )
    {}

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $parsedBody = $request->getParsedBody();

        $password = filter_var($parsedBody['password']);

        if (empty($password)) {
            $this->addErrorMessageAlert('Senha incorreta', 'Tente novamente');
            return new Response(302, ['Location' => '/config/user']);
        }

        $userId = $_SESSION['user_id'];
        $userData = $this->userRepository->findUserById($userId);

        if (password_verify($password, $userData->getPassword())) {
            try {
                $this->userRepository->disableUser($userId);
                session_destroy();
                return new Response(302, ['Location' => '/login']);
            } catch (\PDOException $exception) {
                error_log($exception);
                $this->addErrorMessageAlert('Erro inesperado', 'Tente novamente');
                return new Response(302, ['Location' => '/config/user']);
            }
        }

        $this->addErrorMessageAlert('Senha incorreta', 'Tente novamente');
        return new Response(302, ['Location' => '/config/user']);
    }
}
