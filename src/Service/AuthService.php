<?php

namespace App\Service;

use App\DTO\LoginDTO;
use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\Exception\BadRequestException;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AuthService
{
    public function __construct(
        private UserRepository $userRepository,
        private UserPasswordHasherInterface $hasher
    ){}

    public function login(LoginDTO $loginRequest)
    {
        $loginRepository = $this->userRepository->findBy(['email' => $loginRequest->getEmail()]);
        $isLoginEmpty = empty($loginRepository);

        if ( $isLoginEmpty ) {
            throw new BadRequestException;
        }

        $user = $loginRepository[0];
        $isPasswordValid = $this->hasher->isPasswordValid($user, $loginRequest->getPassword());

        if ( !$isPasswordValid ) {
            throw new BadRequestException;
        }

        return $user;
    }
}