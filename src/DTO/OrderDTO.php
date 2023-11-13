<?php

namespace App\DTO;

use Symfony\Component\HttpFoundation\Request;

class OrderDTO
{
    public function __construct(
        private ?string $cep = null,
        private ?string $number = null,
        private ?string $neighborhood = null,
        private ?string $orderDescription = null,
        private ?string $street = null,
    ){}

    public static function fromRequest(Request $request): self
    {
        $dto = new self();

        $dto->cep = $request->get('cep');
        $dto->number = $request->get('number');
        $dto->neighborhood = $request->get('neighborhood');
        $dto->orderDescription = $request->get('equipment');
        $dto->street = $request->get('street');

        return $dto;
    }

    /**
     * @return string|null
     */
    public function getCep(): ?string
    {
        return $this->cep;
    }

    /**
     * @return string|null
     */
    public function getNumber(): ?string
    {
        return $this->number;
    }

    /**
     * @return string|null
     */
    public function getNeighborhood(): ?string
    {
        return $this->neighborhood;
    }

    /**
     * @return string|null
     */
    public function getOrderDescription(): ?string
    {
        return $this->orderDescription;
    }

    /**
     * @return string|null
     */
    public function getStreet(): ?string
    {
        return $this->street;
    }


}