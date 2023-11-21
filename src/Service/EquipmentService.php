<?php

namespace App\Service;

use App\DTO\EquipmentDTO;
use App\Entity\Donator;
use App\Entity\Equipment;
use Doctrine\ORM\EntityManagerInterface;
use Respect\Validation\Validator as v;
use Symfony\Component\HttpFoundation\Exception\BadRequestException;
use Symfony\Component\HttpFoundation\Response;


class EquipmentService
{
    public function register(EquipmentDTO $equipmentDTO, EntityManagerInterface $entityManager)
    {
        $equipment = $this->validate($equipmentDTO);
        $entityManager->persist($equipment);
        $entityManager->flush();

        return new Response(status: Response::HTTP_OK);
    }

    private function validate(EquipmentDTO $equipmentDTO): Equipment
    {
        $isDonatorEmpty     = !v::stringType()->notEmpty()->validate($equipmentDTO->getDonator());
        $isDescriptionEmpty = !v::stringType()->notEmpty()->validate($equipmentDTO->getDescription());
        $isReceiptDateEmpty =  v::date('d/m/y')->validate($equipmentDTO->getReceiptDate());

        if ( $isDonatorEmpty or $isDescriptionEmpty or $isReceiptDateEmpty ) {
            throw new BadRequestException();
        }

        $donator = new Donator();
        $donator->setName($equipmentDTO->getDonator());

        $receiptDate = new \DateTimeImmutable($equipmentDTO->getReceiptDate());

        $equipment = new Equipment();
        $equipment
            ->setDescription($equipmentDTO->getDescription())
            ->setDonator($donator)
            ->setAvailability(1)
            ->setReceiptDate($receiptDate);

        return $equipment;
    }
}