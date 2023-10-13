<?php

namespace App\DTO;

use Symfony\Component\HttpFoundation\Request;

class LoginDTO
{
    public function __construct(
        private ?string $email = null,
        private ?string $password = null
    ){}

    public static function fromRequest(Request $request): self
    {
        $dto = new self();

        $dto->email = $request->get('email');
        $dto->password = $request->get('password');

        return $dto;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function setEmail(string $email): LoginDTO
    {
        $this->email = $email;
        return $this;
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): LoginDTO
    {
        $this->password = $password;
        return $this;
    }
}