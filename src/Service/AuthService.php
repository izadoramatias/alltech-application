<?php

namespace App\Service;

use App\DTO\LoginDTO;
use App\Repository\UserRepository;
use Respect\Validation\Validator as v;
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
        $isLoginRequestEmpty = !(v::stringType()->notEmpty()->validate($loginRequest->getEmail()));

        if ( $isLoginRequestEmpty ) {
            throw new BadRequestException;
        }

        $loginRepository = $this->userRepository->findBy(['email' => $loginRequest->getEmail()]);
        $loginNotExists = !(v::arrayType()->notEmpty()->validate($loginRepository));

        if ( $loginNotExists ) {
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