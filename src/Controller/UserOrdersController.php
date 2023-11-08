<?php

namespace App\Controller;

use App\Helper\AddressFormatter;
use App\Helper\DataTablesJsonFormatter;
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
    public function renderOrders(Session $session, Request $request): Response
    {
        return new JsonResponse(DataTablesJsonFormatter::format
        (
            AddressFormatter::format($this->getAllUserOrders($session)),
            $request->query->get('draw')
        ));
    }

    private function getAllUserOrders(Session $session): array
    {
        $userEmail = $session->get('userEmail');
        $user = $this->userRepository->findOneBy(['email' => $userEmail]);
        $orders = $this->entityManager->createQuery(
            'SELECT o.id, o.description, o.status, a.street, a.zip_code, a.city, a.district, a.number
            FROM App\Entity\Order o
            JOIN App\Entity\Address a WITH o.address_id = a.id'
        );

        return $orders->getResult();
    }


}