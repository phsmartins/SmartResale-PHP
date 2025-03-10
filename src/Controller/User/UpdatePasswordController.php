<?php

namespace Smart\Resale\Controller\User;

use Nyholm\Psr7\Response;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Smart\Resale\Repository\UserRepository;

readonly class UpdatePasswordController implements RequestHandlerInterface
{
    public function __construct(
        private UserRepository $userRepository,
    )
    {}

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $parsedBody = $request->getParsedBody();

        $oldPassword = filter_var($parsedBody['password']);
        $newPassword = filter_var($parsedBody['new_password']);

        if (empty($oldPassword) || empty($newPassword)) {
            return new Response(302, ['Location' => '/config/user?pass=1']);
        }

        $userId = $_SESSION['user_id'];
        $userData = $this->userRepository->findUserById($userId);

        if (password_verify($oldPassword, $userData->getPassword())) {
            $password = password_hash($newPassword, PASSWORD_ARGON2ID);

            try {
                $this->userRepository->updatePassword($password, $userId);
            } catch (\PDOException $exception) {
                error_log($exception);
                return new Response(302, ['Location' => '/config/user?pass=2']);
            }

            return new Response(302, ['Location' => '/config/user']);
        }

        return new Response(302, ['Location' => '/config/user?pass=3']);
    }
}
