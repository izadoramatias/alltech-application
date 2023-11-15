<?php

namespace App\Service;

use App\DTO\OrderDTO;
use App\Entity\Address;
use App\Entity\Order;
use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Respect\Validation\Validator as v;
use Symfony\Component\HttpFoundation\Exception\BadRequestException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class OrderService
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private UserRepository $userRepository,
    ){}

    public function register(OrderDTO $orderDTO, Session $session): Response
    {
        try {
            $order = $this->validateOrderData($orderDTO, $session);
            $this->entityManager->merge($order);
            $this->entityManager->flush();
        } catch (BadRequestException $exception) {
            throw new BadRequestException();
        }
        return new Response(status: Response::HTTP_OK);
    }

    public function registerEditedOrder(Order $order, OrderDTO $orderDTO)
    {
        try {
            $editedOrder = $this->validatePutOrder($order, $orderDTO);
            $this->entityManager->flush();
        } catch (BadRequestHttpException) {
            throw new BadRequestException();
        }
        return new Response(status: Response::HTTP_OK);
    }

    private function validateOrderData(OrderDTO $orderDTO, Session $session): Order
    {
        $isCepEmpty          = !v::stringType()->notEmpty()->validate($orderDTO->getCep());
        $isNumberEmpty       = !v::stringType()->notEmpty()->validate($orderDTO->getNumber());
        $isNeighborhoodEmpty = !v::stringType()->notEmpty()->validate($orderDTO->getNeighborhood());
        $isEquipmentEmpty    = !v::stringType()->notEmpty()->validate($orderDTO->getOrderDescription());
        $isStreetEmpty       = !v::stringType()->notEmpty()->validate($orderDTO->getStreet());

        if ( $isCepEmpty or $isNumberEmpty or $isNeighborhoodEmpty or $isEquipmentEmpty or $isStreetEmpty ) {
            throw new BadRequestException();
        }

        $userEmail = $session->get('userEmail');
        $userRepository = ($this->entityManager->getRepository(User::class)->findBy(['email' => $userEmail]))[0];
        $user = new User();
        $user->setPermission($userRepository->getPermission());
        $user->setId($userRepository->getId());
        $user->setFullName($userRepository->getFullName());
        $user->setEmail($userRepository->getEmail());
        $user->setPhone($userRepository->getPhone());
        $user->setPassword($userRepository->getPassword());

        $address = new Address();
        $address->setZipCode($orderDTO->getCep());
        $address->setNumber($orderDTO->getNumber());
        $address->setDistrict($orderDTO->getNeighborhood());
        $address->setStreet($orderDTO->getStreet());
        $address->setCity('Garça');

        $order = new Order();
        $order->setDescription($orderDTO->getOrderDescription());
        $order->setStatus(1);
        $order->setAddressId($address);
        $order->setUserId($user);

        return $order;
    }

    public function validatePutOrder(Order $order, OrderDTO $orderDTO): Order
    {
        $isCepEmpty          = !v::stringType()->notEmpty()->validate($orderDTO->getCep());
        $isNumberEmpty       = !v::stringType()->notEmpty()->validate($orderDTO->getNumber());
        $isNeighborhoodEmpty = !v::stringType()->notEmpty()->validate($orderDTO->getNeighborhood());
        $isEquipmentEmpty    = !v::stringType()->notEmpty()->validate($orderDTO->getOrderDescription());
        $isStreetEmpty       = !v::stringType()->notEmpty()->validate($orderDTO->getStreet());

        if ( $isCepEmpty or $isNumberEmpty or $isNeighborhoodEmpty or $isEquipmentEmpty or $isStreetEmpty ) {
            throw new BadRequestException();
        }

        $editedAddress = $order->getAddressId();
        $editedAddress->setZipCode($orderDTO->getCep());
        $editedAddress->setCity('Garça');
        $editedAddress->setDistrict($orderDTO->getNeighborhood());
        $editedAddress->setStreet($orderDTO->getStreet());
        $editedAddress->setNumber($orderDTO->getNumber());

        $editedOrder = $order
            ->setDescription($orderDTO->getOrderDescription())
            ->setAddressId($editedAddress);

        return $editedOrder;
    }
}