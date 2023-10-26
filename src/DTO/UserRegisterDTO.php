<?php

namespace App\DTO;

use Symfony\Component\HttpFoundation\Request;

class UserRegisterDTO
{
    public function __construct(
        private ?string $fullName = null,
        private ?string $email = null,
        private ?string $phone = null,
        private ?string $password = null,
    ){}

    public static function fromRequest(Request $request): self
    {
        $dto = new self();


        $dto->fullName = $request->get('name');
        $dto->email = $request->get('email');
        $dto->phone = $request->get('phone');
        $dto->password = $request->get('password');

        return $dto;
    }

    /**
     * @return string|null
     */
    public function getFullName(): ?string
    {
        return $this->fullName;
    }

    /**
     * @return string|null
     */
    public function getEmail(): ?string
    {
        return $this->email;
    }

    /**
     * @return string|null
     */
    public function getPhone(): ?string
    {
        return $this->phone;
    }

    /**
     * @return string|null
     */
    public function getPassword(): ?string
    {
        return $this->password;
    }
}