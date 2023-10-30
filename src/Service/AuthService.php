<?php

namespace App\Service;

use App\DTO\LoginDTO;
use App\DTO\UserRegisterDTO;
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

    public function register(UserRegisterDTO $userRegisterRequest)
    {
        $isNameEmpty                 = !v::stringType()->notEmpty()->validate($userRegisterRequest->getFullName());
        $isEmailEmpty                = !v::stringType()->notEmpty()->validate($userRegisterRequest->getEmail());
        $isPhoneEmpty                = !v::stringType()->notEmpty()->validate($userRegisterRequest->getPhone());
        $isPasswordEmpty             = !v::stringType()->notEmpty()->validate($userRegisterRequest->getPassword());
        $isPasswordConfirmationEmpty = !v::stringType()->notEmpty()->validate($userRegisterRequest->getPasswordConfirmation());

        if ( $isNameEmpty or $isEmailEmpty or $isPhoneEmpty or $isPasswordEmpty or $isPasswordConfirmationEmpty) {
            throw new BadRequestException();
        }

    }
}