<?php

namespace App\Service;

use App\DTO\LoginDTO;
use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\Exception\BadRequestException;

class AuthService
{
    public function __construct(
        private UserRepository $userRepository
    ){}

    public function login(LoginDTO $login)
    {
        $login = $this->userRepository->findBy(['email' => $login->getEmail()]);

        if (empty($login)) {
            throw new BadRequestException;
        }
        return $login[0];
    }
}