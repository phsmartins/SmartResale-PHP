<?php

namespace Smart\Resale\Controller\User;

use Nyholm\Psr7\Response;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Smart\Resale\Entity\User;
use Smart\Resale\Repository\UserRepository;

readonly class UpdateUserController implements RequestHandlerInterface
{
    public function __construct(
        private UserRepository $userRepository,
    )
    {}

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $parsedBody = $request->getParsedBody();

        $name = filter_var($parsedBody['name'], FILTER_SANITIZE_SPECIAL_CHARS);
        $email = filter_var($parsedBody['email'], FILTER_VALIDATE_EMAIL);

        if (empty($name)) {
            return new Response(302, ['Location' => '/config/user?error=name']);
        }

        if ($email === false || $email === null) {
            return new Response(302, ['Location' => '/config/user?error=email']);
        }

        $user = new User(
            $name,
            $email,
        );
        $user->setId($_SESSION['user_id']);

        try {
            if (!$this->userRepository->updateUser($user)) {
                return new Response(302, ['Location' => '/config/user?error=error2']);
            }
        } catch (\LogicException $exception) {
            return new Response(302, ['Location' => '/config/user?error=email2']);
        } catch (\PDOException $exception) {
            return new Response(302, ['Location' => '/config/user?error=error']);
        }

        return new Response(302, ['Location' => '/config/user']);
    }
}
