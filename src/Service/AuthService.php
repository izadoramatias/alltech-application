<?php

namespace App\Service;

use App\DTO\LoginDTO;
use App\DTO\UserRegisterDTO;
use App\Entity\Permission;
use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Respect\Validation\Validator as v;
use Symfony\Component\HttpFoundation\Exception\BadRequestException;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\ConflictHttpException;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AuthService
{
    CONST ADM_PERMISSION = 'ADM';
    CONST COMMOM_PERMISSION = 'COMMOM';

    public function __construct(
        private UserRepository $userRepository,
        private UserPasswordHasherInterface $hasher,
        private EntityManagerInterface $entityManager
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
        $commomPermission = $this->entityManager->getRepository(Permission::class)->findOneBy(['name' => self::COMMOM_PERMISSION]);
        $emailAlreadyExists = $this->entityManager->getRepository(User::class)->findOneBy(['email' => $userRegisterRequest->getEmail()]) ;

        if ( $emailAlreadyExists ) {
            throw new ConflictHttpException();
        }

        if ( $isNameEmpty or $isEmailEmpty or $isPhoneEmpty or $isPasswordEmpty) {
            throw new BadRequestException();
        }

        $user = new User();
        $user
            ->setFullName($userRegisterRequest->getFullName())
            ->setEmail($userRegisterRequest->getEmail())
            ->setPassword($userRegisterRequest->getPassword())
            ->setPhone($userRegisterRequest->getPhone())
            ->setPermission($commomPermission);

        $this->entityManager->persist($user);
        $this->entityManager->flush();

        return new Response(status: 200);
    }
}