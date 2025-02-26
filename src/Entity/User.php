<?php

namespace Smart\Resale\Entity;

class User
{
    private int $id;
    private string $name;
    private string $email;
    private ?string $password;
    private int $isActive;
    private string $createdAt;
    private ?string $updatedAt;

    public function __construct(
        string $name,
        string $email,
        string $password = null
    )
    {
        $this->setName($name);
        $this->setEmail($email);
        $this->setPassword($password);
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id): void
    {
        if (!filter_var($id, FILTER_VALIDATE_INT)) {
            throw new \InvalidArgumentException('O ID do usuário deve ser um inteiro');
        }

        $this->id = $id;
    }

    public function getName(): string
    {
        return mb_convert_case($this->name, MB_CASE_TITLE);
    }

    public function setName(string $name): void
    {
        $this->name = filter_var($name, FILTER_SANITIZE_SPECIAL_CHARS);
    }

    public function getEmail(): string
    {
        if (!filter_var($this->email, FILTER_VALIDATE_EMAIL)) {
            throw new \InvalidArgumentException('Erro ao buscar e-mail');
        }

        return $this->email;
    }

    public function setEmail(string $email): void
    {
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new \InvalidArgumentException('E-mail inválido');
        }

        $this->email = $email;
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(?string $password): void
    {
        if ($password !== null) {
            $this->password = password_hash($password, PASSWORD_ARGON2ID);
        }
    }

    public function getIsActive(): int
    {
        return $this->isActive;
    }

    public function setIsActive(int $isActive = 1): void
    {
        $this->isActive = $isActive;
    }

    public function getCreatedAt(): string
    {
        return $this->createdAt;
    }

    public function setCreatedAt(string $createdAt): void
    {
        $this->createdAt = $createdAt;
    }

    public function getUpdatedAt(): ?string
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(?string $updatedAt = null): void
    {
        $this->updatedAt = $updatedAt;
    }
}
