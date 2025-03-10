<?php

namespace Smart\Resale\Controller\User;

use Nyholm\Psr7\Response;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Smart\Resale\Repository\UserRepository;

class DisableAccountController implements RequestHandlerInterface
{
    public function __construct(
        private UserRepository $userRepository,
    )
    {}

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $parsedBody = $request->getParsedBody();

        $password = filter_var($parsedBody['password']);

        if (empty($password)) {
            return new Response(302, ['Location' => '/config/user?delete=1']);
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
                return new Response(302, ['Location' => '/config/user?delete=2']);
            }
        }

        return new Response(302, ['Location' => '/config/user?delete=3']);
    }
}
