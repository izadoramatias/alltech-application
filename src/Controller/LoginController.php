<?php

namespace App\Controller;

use App\DTO\LoginDTO;
use App\Service\AuthService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Exception\BadRequestException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Routing\Annotation\Route;

class LoginController extends AbstractController
{
    const ADM_PERMISSION = 3;
    const COMMOM_PERMISSION = 4;

    #[Route('/login', name: 'app_login_render', methods: ['GET'])]
    public function renderLoginForm(): Response
    {
        return $this->render('login.html.twig');
    }

    #[Route('/login', name: 'app_login_request_process', methods: ['POST'])]
    public function processLoginRequest(
        Request $request,
        AuthService $auth,
        Session $session
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

        $userPermission = $loginAuth->getPermission()->getId();
        $userEmail = $loginAuth->getEmail();

        $session->start();
        $session->set('isUserLogged', true);
        $session->set('userEmail', $userEmail);
        $session->set('userPermission', $userPermission);

        if ( $userPermission === self::ADM_PERMISSION ) {
            return $this->redirectToRoute('app_adm_dashboard_render');
        }
        return $this->redirectToRoute('app_user_order_listing_render');
    }

    private function loginData(LoginDTO $login): array
    {
        $loginData = [
            'email' => $login->getEmail(),
            'password' => $login->getPassword(),
        ];

        return $loginData;
    }

    #[Route('/logout', name: 'app_user_logout', methods: ['GET'])]
    public function processLogoutRequest(Session $session): Response
    {
        $session->invalidate();
        return $this->redirectToRoute('app_login_render');
    }
}
