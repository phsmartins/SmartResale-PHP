<?php

namespace Smart\Resale\Controller\User;

use Nyholm\Psr7\Response;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Smart\Resale\Entity\User;
use Smart\Resale\Repository\UserRepository;
use Smart\Resale\Traits\FlashMessageTrait;

readonly class UpdateUserController implements RequestHandlerInterface
{
    use FlashMessageTrait;

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
            $this->addErrorMessageAlert("'Nome' é um campo obrigatório");
            return new Response(302, ['Location' => '/config/user']);
        }

        if ($email === false || $email === null) {
            $this->addErrorMessageAlert('Digite um e-mail válido');
            return new Response(302, ['Location' => '/config/user']);
        }

        $user = new User(
            $name,
            $email,
        );
        $user->setId($_SESSION['user_id']);

        try {
            if (!$this->userRepository->updateUser($user)) {
                $this->addErrorMessageAlert('Erro inesperado', 'Tente novamente');
                return new Response(302, ['Location' => '/config/user']);
            }
        } catch (\LogicException $exception) {
            $this->addErrorMessageAlert('Esse e-mail já existe na base de dados');
            return new Response(302, ['Location' => '/config/user']);
        } catch (\PDOException $exception) {
            $this->addErrorMessageAlert('Erro inesperado', 'Tente novamente');
            return new Response(302, ['Location' => '/config/user']);
        }

        $this->addSuccessMessageAlert('Dados atualizados com sucesso', 'Bom trabalho');
        return new Response(302, ['Location' => '/config/user']);
    }
}
