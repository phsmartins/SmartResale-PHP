<?php

namespace Smart\Resale\Controller\User;

use Nyholm\Psr7\Response;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Smart\Resale\Repository\UserRepository;
use Smart\Resale\Traits\FlashMessageTrait;

readonly class LoginController implements RequestHandlerInterface
{
    use FlashMessageTrait;

    public function __construct(
        private UserRepository $userRepository,
    )
    {}

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $parsedBody = $request->getParsedBody();

        $email = filter_var($parsedBody['email'], FILTER_VALIDATE_EMAIL);
        $password = filter_var($parsedBody['password']);

        if ($email === false || $email === null) {
            $this->addErrorMessage('Digite um e-mail válido');
            return new Response(302, ['Location' => '/login']);
        }

        $_SESSION['user_email_login'] = $email;

        $userData = $this->userRepository->findUserByEmail($email);

        if ($userData === null) {
            $this->addErrorMessage('E-mail ou senha inválidos. Tente novamente');
            return new Response(302, ['Location' => '/login']);
        }

        if ($userData->getIsActive() === 0) {
            $this->addErrorMessage('Consta desativada. Ainda vou criar a funcionalidade para reativar &#128517;');
            return new Response(302, ['Location' => '/login']);
        }

        if (password_verify($password, $userData->getPassword() ?? '')) {
            if (password_needs_rehash($userData->getPassword(), PASSWORD_ARGON2ID)) {
                $this->userRepository->updatePassword($userData->getPassword(), $userData->getId());
            }

            $_SESSION['logged_in'] = true;
            $_SESSION['user_id'] = $userData->getId();
            return new Response(302, ['Location' => '/']);
        }

        $this->addErrorMessage('E-mail ou senha inválidos. Tente novamente');
        return new Response(302, ['Location' => '/login']);
    }
}
