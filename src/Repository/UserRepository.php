<?php

namespace Smart\Resale\Repository;

use Smart\Resale\Entity\User;

readonly class UserRepository
{
    public function __construct(private \PDO $pdo)
    {}

    public function checkIfEmailExists(string $email): bool
    {
        try {
            $query = 'SELECT COUNT(*) FROM users WHERE email = :email;';

            $statement = $this->pdo->prepare($query);
            $statement->bindValue(':email', $email);

            if (!$statement->execute()) {
                return false;
            }

            $existsEmail = $statement->fetchColumn();

            if ($existsEmail > 0) {
                throw new \LogicException('Este e-mail já está associado a uma conta');
            }

            return true;
        } catch (\PDOException $exception) {
            error_log('Erro ao buscar e-mail', $exception->getMessage());
            return false;
        }
    }

    public function addUser(User $user): bool
    {
        try {
            if (!$this->checkIfEmailExists($user->getEmail())) {
                return false;
            }

            $userCreationDate = (new \DateTime())->format('Y-m-d H:i:s');
            $user->setCreatedAt($userCreationDate);

            $query = '
                INSERT INTO users (
                    name, email, password, is_active, created_at           
                ) VALUES (
                    :name, :email, :password, :is_active, :created_at        
                );
            ';

            $statement = $this->pdo->prepare($query);
            $statement->bindValue(':name', $user->getName());
            $statement->bindValue(':email', $user->getEmail());
            $statement->bindValue(':password', $user->getPassword());
            $statement->bindValue(':is_active', $user->getIsActive());
            $statement->bindValue(':created_at', $user->getCreatedAt());

            $userResultAdded = $statement->execute();

            if (!$userResultAdded) {
                return false;
            }

            $id = $this->pdo->lastInsertId();
            if ($id) {
                $user->setId((int) $id);
            }

            return true;
        } catch (\PDOException $exception) {
            error_log('Erro ao adicionar usuário: ' . $exception->getMessage());
            return false;
        }
    }

    public function findUserById(int $id): ?User
    {
        try {
            $query = '
                SELECT * FROM users WHERE id = :id
            ';

            $statement = $this->pdo->prepare($query);
            $statement->bindValue(':id', $id);

            if (!$statement->execute()) {
                return null;
            }

            return $this->hydrateUser($statement->fetch(\PDO::FETCH_ASSOC));
        } catch (\PDOException $exception) {
            error_log('Erro ao buscar dados do usuário: ' . $exception->getMessage());
            return null;
        }
    }

    public function findUserByEmail(string $email): ?User
    {
        try {
            $query = '
                SELECT * FROM users WHERE email = :email
            ';

            $statement = $this->pdo->prepare($query);
            $statement->bindValue(':email', $email);

            if (!$statement->execute()) {
                return null;
            }

            return $this->hydrateUser($statement->fetch(\PDO::FETCH_ASSOC));
        } catch (\PDOException $exception) {
            error_log('Erro ao buscar dados do usuário: ' . $exception->getMessage());
            return null;
        }
    }

    private function hydrateUser(array $userData): User
    {
        $user = new User(
            $userData['name'],
            $userData['email'],
        );

        $user->setId($userData['id']);
        $user->setPassword($userData['password']);
        $user->setIsActive($userData['is_active']);
        $user->setCreatedAt($userData['created_at']);
        $user->setUpdatedAt($userData['updated_at']);

        return $user;
    }
}
