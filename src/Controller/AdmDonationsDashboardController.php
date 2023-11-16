<?php

namespace App\Controller;

use App\Entity\Order;
use App\Helper\DataTablesJsonFormatter;
use App\Helper\OrderFormatter;
use App\Repository\OrderRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Routing\Annotation\Route;

class AdmDonationsDashboardController extends AbstractController
{
    public function __construct(
        private OrderRepository $orderRepository,
        private EntityManagerInterface $entityManager
    ){}

    #[Route('/dashboard', name: 'app_adm_dashboard_render', methods: ['GET'])]
    public function renderDashboard(Session $session): Response
    {
        if ( !$session->has('isUserLogged') ) {
            $response = $this->redirectToRoute('app_login_render');
            $response->setStatusCode(Response::HTTP_FORBIDDEN);

            return $response;
        }

        if ( $session->isStarted() && $session->get('userPermission') === 3 ) {
            return $this->render('admDashboardDonations.html.twig');
        }

        return $this->redirectToRoute('app_user_order_listing_render');
    }

    #[Route('/pendant-orders', name: 'app_admdashboard_pendant_orders', methods: ['GET'])]
    public function renderOrders(Request $request): Response
    {
        return new JsonResponse(DataTablesJsonFormatter::format
        (
            $this->formatPendingOrders($this->getAllUserOrders()),
            $request->query->get('draw')
        ));
    }

    private function getAllUserOrders(): array
    {
        $orders = $this->entityManager->getRepository(Order::class)->findAll();
        return $orders;
    }

    private function formatPendingOrders(array $orders): array
    {
        $formatedOrders = [];

        foreach ($orders as $order) {
            $zip_code = $order->getAddressId()->getZipCode();
            $city = $order->getAddressId()->getCity();
            $district = $order->getAddressId()->getDistrict();
            $street = $order->getAddressId()->getStreet();
            $number = $order->getAddressId()->getNumber();

            $formatedOrders[] = [
                'id' => $order->getId(),
                'description' => $order->getDescription(),
                'status' => $order->getStatus() === 1 ? 'Em Andamento' : 'Concluído',
                'address' =>
                    [
                        'cep' => $zip_code,
                        'city' => $city,
                        'neighborhood' => $district,
                        'street' => $street,
                        'number' => $number
                    ],
                'user' =>
                    [
                        'name' => $order->getUserId()->getFullName(),
                        'phone' => $order->getUserId()->getPhone(),
                        'email' => $order->getUserId()->getEmail()
                    ]

            ];
        }

        return $formatedOrders;
    }
}