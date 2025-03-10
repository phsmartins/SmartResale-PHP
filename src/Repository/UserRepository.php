<?php

namespace Smart\Resale\Repository;

use Smart\Resale\Entity\User;

readonly class UserRepository
{
    public function __construct(private \PDO $pdo)
    {}

    public function checkIfEmailExists(string $email, int $id = null): bool
    {
        if ($id !== null) {
            try {
                $query = 'SELECT email FROM users WHERE id = :id;';

                $statement = $this->pdo->prepare($query);
                $statement->bindValue(':id', $id);

                if (!$statement->execute()) {
                    return false;
                }

                $emailFound = $statement->fetch(\PDO::FETCH_ASSOC);

                if ($email === $emailFound['email']) {
                    return true;
                }
            } catch (\PDOException $exception) {
                error_log('Erro ao buscar e-mail', $exception->getMessage());
                return false;
            }
        }

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

    public function updateUser(User $user): bool
    {
        try {
            if (!$this->checkIfEmailExists($user->getEmail(), $user->getId())) {
                return false;
            }

            $userUpdateDate = (new \DateTime())->format('Y-m-d H:i:s');
            $user->setUpdatedAt($userUpdateDate);

            $query = '
                UPDATE users SET
                    name = :name,
                    email = :email,
                    updated_at = :updated_at
                WHERE id = :id;
            ';

            $statement = $this->pdo->prepare($query);

            $statement->bindValue(':name', $user->getName());
            $statement->bindValue(':email', $user->getEmail());
            $statement->bindValue(':updated_at', $user->getUpdatedAt());
            $statement->bindValue(':id', $user->getId());

            return $statement->execute();
        } catch (\PDOException $exception) {
            error_log('Erro ao atualizar usuário', $exception->getMessage());
            return false;
        }
    }

    public function updatePassword(string $password, int $id): bool
    {
        try {
            $querySql = "
                UPDATE users SET
                    password = :password 
                WHERE id = :id;
            ";

            $statement = $this->pdo->prepare($querySql);

            $statement->bindValue(':password', $password);
            $statement->bindValue(':id', $id);

            return $statement->execute();
        } catch (\PDOException $exception) {
            error_log('Erro ao atualizar senh: ' . $exception);
            return false;
        }
    }

    public function disableUser(int $id): bool
    {
        try {
            $query = '
                UPDATE users SET
                    is_active = 0,
                    deleted_at = :deleted_at
                WHERE id = :id;
            ';

            $statement = $this->pdo->prepare($query);

            $userDeletionDate = (new \DateTime())->format('Y-m-d H:i:s');
            $statement->bindValue(':deleted_at', $userDeletionDate);
            $statement->bindValue(':id', $id);

            return $statement->execute();
        } catch (\PDOException $exception) {
            error_log('Não foi possível desativar a conta', $exception->getMessage());
            return false;
        }
    }

    public function reactivateUser(string $email): bool
    {
        try {
            $query = '
                UPDATE users SET
                    is_active = 1,
                    restored_at = :restored_at
                WHERE email = :email;
            ';

            $statement = $this->pdo->prepare($query);

            $userReactivationDate = (new \DateTime())->format('Y-m-d H:i:s');
            $statement->bindValue(':restored_at', $userReactivationDate);
            $statement->bindValue(':email', $email);

            return $statement->execute();
        } catch (\PDOException $exception) {
            error_log('Não foi possível reativar a conta', $exception->getMessage());
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

            $statementReturn = $statement->fetch(\PDO::FETCH_ASSOC);

            if (!$statementReturn) {
                return null;
            }

            return $this->hydrateUser($statementReturn);
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

            $statementReturn = $statement->fetch(\PDO::FETCH_ASSOC);

            if (!$statementReturn) {
                return null;
            }

            return $this->hydrateUser($statementReturn);
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
