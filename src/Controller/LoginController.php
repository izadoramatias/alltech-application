<?php

namespace App\Controller;

use App\DTO\LoginDTO;
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
    public function processLoginRequest(Request $request): Response
    {
        $login = LoginDTO::fromRequest($request);

        return new Response('ok');
    }
}