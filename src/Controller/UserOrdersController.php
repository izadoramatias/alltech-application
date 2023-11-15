<?php

namespace App\Controller;

use App\DTO\OrderDTO;
use App\Entity\Order;
use App\Helper\DataTablesJsonFormatter;
use App\Helper\OrderFormatter;
use App\Repository\OrderRepository;
use App\Repository\UserRepository;
use App\Service\OrderService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Exception\BadRequestException;
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

    #[Route('/order', name: 'app_order_register_render', methods: ['GET'])]
    public function registerOrderRender(): Response
    {
        return $this->render('userOrderFormRegister.html.twig', response: new Response(200));
    }

    #[Route('/order', name: 'app_process_order_register_request', methods: ['POST'])]
    public function processRegisterOrderRequest(Request $request, OrderService $orderService, Session $session): Response
    {
        $order = OrderDTO::fromRequest($request);
        try {
            $orderService->register($order, $session);
        } catch ( BadRequestException $exception ) {
            return $this->render('userOrderFormRegisterFailed.html.twig', $this->formattedOrderData($order), new Response(Response::HTTP_BAD_REQUEST));
        }

        toastr()
            ->closeOnHover(true)
            ->closeDuration(10)
            ->addSuccess('Pedido registrado com sucesso', 'Sucesso');
        return $this->redirectToRoute('app_user_order_listing_render');
    }

    #[Route('/order/edit/{id}', name: 'app_user_order_edit', methods: ['GET'])]
    public function processFormEditOrderRequest($id): Response
    {
        $order = $this->orderRepository->findBy(['id' => $id]);
        if ( empty($order) ) {
            return $this->redirectToRoute('app_user_order_listing_render')
                ->setStatusCode(Response::HTTP_BAD_REQUEST, 'O id de pedido informado é inválido.');
        }

        return $this->render('userOrderFormEdit.html.twig', parameters: $this->registerData($order[0]), response: new Response(Response::HTTP_OK));
    }

    #[Route('/order/edit/{id}/save', name: 'app_user_order_edit_save')]
    public function processSaveEditedOrderRequest($id, OrderService $orderService, Request $request): Response
    {
        $order = $this->entityManager->getRepository(Order::class)->findBy(['id' => $id]);
        $orderDTO = OrderDTO::fromRequest($request);

        try {
            $orderService->registerEditedOrder($order[0], $orderDTO);
        } catch ( BadRequestException $exception ) {
            return $this->render('userOrderFormEdit.html.twig', parameters: $this->formattedOrderData($orderDTO, $id), response: new Response(Response::HTTP_BAD_REQUEST));
        }

        toastr()
            ->closeOnHover(true)
            ->closeDuration(10)
            ->addSuccess('Pedido editado com sucesso', 'Sucesso');
        return $this->redirectToRoute('app_user_order_listing_render');
    }

    private function getAllUserOrders(): array
    {
        $orders = $this->orderRepository->findAllOrders();
        return $orders->getResult();
    }

    private function formattedOrderData(OrderDTO $orderDTO, int $id = 0): array
    {
        return [
            'id'               => $id,
            'cep'              => $orderDTO->getCep(),
            'neighborhood'     => $orderDTO->getNeighborhood(),
            'number'           => $orderDTO->getNumber(),
            'orderDescription' => $orderDTO->getOrderDescription(),
            'street'           => $orderDTO->getStreet()
        ];
    }

    private function registerData(Order $order): array
    {
        return [
            'id'                => $order->getId(),
            'orderDescription'  => $order->getDescription(),
            'cep'               => $order->getAddressId()->getZipCode(),
            'neighborhood'      => $order->getAddressId()->getDistrict(),
            'street'            => $order->getAddressId()->getStreet(),
            'number'            => $order->getAddressId()->getNumber()
        ];
    }
}