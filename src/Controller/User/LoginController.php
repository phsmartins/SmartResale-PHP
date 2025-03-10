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
    ) {}

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $parsedBody = $request->getParsedBody();

        $email = filter_var($parsedBody['email'], FILTER_VALIDATE_EMAIL);
        $password = filter_var($parsedBody['password']);

        if (!$this->isValidEmail($email)) {
            return $this->redirectWithError('/login', 'Digite um e-mail válido');
        }

        $_SESSION['user_email_login'] = $email;

        $userData = $this->userRepository->findUserByEmail($email);

        if (!$this->isValidUser($userData)) {
            return $this->redirectWithError('/login', 'E-mail ou senha inválidos. Tente novamente');
        }

        if ($this->isInactiveUser($userData)) {
            return $this->redirectWithError('/login', 'Conta desativada. Ainda vou criar a funcionalidade para reativar &#128517;');
        }

        if (!$this->isValidPassword($password, $userData)) {
            return $this->redirectWithError('/login', 'E-mail ou senha inválidos. Tente novamente');
        }

        $this->loginUser($userData);
        return new Response(302, ['Location' => '/']);
    }

    private function isValidEmail(?string $email): bool
    {
        return $email !== false && $email !== null;
    }

    private function isValidUser(?object $userData): bool
    {
        return $userData !== null;
    }

    private function isInactiveUser(object $userData): bool
    {
        return $userData->getIsActive() === 0;
    }

    private function isValidPassword(string $password, object $userData): bool
    {
        if (!password_verify($password, $userData->getPassword() ?? '')) {
            return false;
        }

        if (password_needs_rehash($userData->getPassword(), PASSWORD_ARGON2ID)) {
            $this->userRepository->updatePassword($userData->getPassword(), $userData->getId());
        }

        return true;
    }

    private function loginUser(object $userData): void
    {
        $_SESSION['logged_in'] = true;
        $_SESSION['user_id'] = $userData->getId();
    }

    private function redirectWithError(string $location, string $message): ResponseInterface
    {
        $this->addErrorMessage($message);
        return new Response(302, ['Location' => $location]);
    }
}
