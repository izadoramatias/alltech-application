<?php

namespace App\Controller;

use App\Helper\DataTablesJsonFormatter;
use App\Helper\OrderFormatter;
use App\Repository\OrderRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Routing\Annotation\Route;

class UserOrdersController extends AbstractController
{
    public function __construct(
        private OrderRepository $orderRepository,
        private UserRepository $userRepository,
        private EntityManagerInterface $entityManager
    ){}

    #[Route('/home', name: 'app_user_order_listing_render', methods: ['GET'])]
    public function renderHome(Session $session): Response
    {
        if ( !$session->has('isUserLogged') ){
            $response = $this->redirectToRoute('app_login_render');
            $response->setStatusCode(Response::HTTP_FORBIDDEN);

            return $response;
        }

        return $this->render('userOrderList.html.twig');
    }

    #[Route('/orders', name: 'app_home_orders_rendering', methods: ['GET'])]
    public function renderOrders(Request $request): Response
    {
        return new JsonResponse(DataTablesJsonFormatter::format
        (
            OrderFormatter::format($this->getAllUserOrders()),
            $request->query->get('draw')
        ));
    }

    private function getAllUserOrders(): array
    {
        $orders = $this->orderRepository->findAllOrders();
        return $orders->getResult();
    }


}