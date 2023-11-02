<?php

namespace App\Controller;

use App\DTO\UserRegisterDTO;
use App\Service\AuthService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Exception\BadRequestException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\ConflictHttpException;
use Symfony\Component\Routing\Annotation\Route;

class UserRegistrationController extends AbstractController
{
    #[Route('/register', name: 'app_userregister_render', methods: ['GET'])]
    public function renderUserRegistrationForm(): Response
    {
        return $this->render('userRegister.html.twig');
    }

    #[Route('/register', name: 'app_register_request_process', methods: ['POST'])]
    public function processRegisterRequest(Request $request, AuthService $authService): Response
    {
        $register = UserRegisterDTO::fromRequest($request);

        try {
            $userRegisterAuth = $authService->register($register);
        } catch ( BadRequestException $exception ) {
            return $this->render(view: 'userRegisterFailed.html.twig', parameters: $this->registerData($register) , response: new Response(status: 400));
        } catch ( ConflictHttpException $exception ) {
            toastr()
                ->closeOnHover(true)
                ->closeDuration(10)
                ->addError('O email informado já está cadastrado', 'Erro');
            return $this->render(view: 'userRegisterFailed.html.twig', parameters: $this->registerData($register) , response: new Response(status: 409));
        }

        return $this->redirectToRoute('app_user_order_listing_render');
    }

    private function registerData(UserRegisterDTO $userRegisterDTO): array
    {
        return [
            'name' => $userRegisterDTO->getFullName(),
            'email' => $userRegisterDTO->getEmail(),
            'phone' => $userRegisterDTO->getPhone(),
            'password' => $userRegisterDTO->getPassword(),
        ];
    }
}