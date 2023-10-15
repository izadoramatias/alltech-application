<?php

namespace App\Controller;

use App\DTO\LoginDTO;
use App\Entity\User;
use App\Service\AuthService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class LoginController extends AbstractController
{
    #[Route('/login', name: 'app_login_render', methods: ['GET'])]
    public function renderLoginForm(): Response
    {
        return $this->render('login.html.twig');
    }

    #[Route('/login', name: 'app_login_request_process')]
    public function processLoginRequest(
        Request $request,
        AuthService $auth
    )
    {
        $login = LoginDTO::fromRequest($request);
        $loginData = $this->jsonSerialize($auth->login($login));

        return $this->render('userOrderList.html.twig');
    }


    public function jsonSerialize(User $user)
    {
        $userArray = [
            'name' => $user->getFullName(),
            'email' => $user->getEmail(),
            'permission' => $user->getPermission()
        ];

        return json_encode($userArray);
    }
}