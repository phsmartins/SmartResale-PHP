<?php

namespace Smart\Resale\Controller\User;

use Nyholm\Psr7\Response;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Smart\Resale\Repository\UserRepository;
use Smart\Resale\Traits\FlashMessageTrait;

readonly class UpdatePasswordController implements RequestHandlerInterface
{
    use FlashMessageTrait;

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
            $this->addErrorMessageAlert('Preecha todos os campos de senha');
            return new Response(302, ['Location' => '/config/user']);
        }

        $userId = $_SESSION['user_id'];
        $userData = $this->userRepository->findUserById($userId);

        if (password_verify($oldPassword, $userData->getPassword())) {
            $password = password_hash($newPassword, PASSWORD_ARGON2ID);

            try {
                $this->userRepository->updatePassword($password, $userId);
            } catch (\PDOException $exception) {
                error_log($exception);
                $this->addErrorMessageAlert('Erro inesperado', 'Tente novamente');
                return new Response(302, ['Location' => '/config/user']);
            }

            $this->addSuccessMessageAlert('Senha atualizada com sucesso', 'Bom trabalho');
            return new Response(302, ['Location' => '/config/user']);
        }

        $this->addErrorMessageAlert('Senha incorreta', 'Tente novamente');
        return new Response(302, ['Location' => '/config/user?pass=3']);
    }
}
