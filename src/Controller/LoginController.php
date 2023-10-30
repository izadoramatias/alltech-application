<?php

namespace App\Controller;

use App\DTO\LoginDTO;
use App\Service\AuthService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Exception\BadRequestException;
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
        AuthService $auth,
    ): Response
    {
        $login = LoginDTO::fromRequest($request);

        try {
            $loginAuth = $auth->login($login);
        } catch (BadRequestException $exception) {
            toastr()
                ->closeOnHover(true)
                ->closeDuration(10)
                ->addError('O email ou senha informados são inválidos!', 'Erro');
            return $this->render(view: 'loginUnauthorized.html.twig', parameters: $this->loginData($login) , response: new Response(status: 401));
        }

        return $this->render(
            view: 'userOrderList.html.twig',
            response: new Response(status: 200));
    }

    private function loginData(LoginDTO $login): array
    {
        $loginData = [
            'email' => $login->getEmail(),
            'password' => $login->getPassword(),
        ];

        return $loginData;
    }
}
