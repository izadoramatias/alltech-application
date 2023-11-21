<?php

namespace App\DTO;

use App\Entity\Donator;
use Symfony\Component\HttpFoundation\Request;

class EquipmentDTO
{
    public function __construct(
        private ?string $description = null,
        private ?string $receiptDate = null,
        private ?int $availability = null,
        private ?string $donator = null,
        private ?string $submitType = null
    ){}

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): EquipmentDTO
    {
        $this->description = $description;
        return $this;
    }

    public function getReceiptDate(): ?string
    {
        return $this->receiptDate;
    }

    public function setReceiptDate(?string $receiptDate): EquipmentDTO
    {
        $this->receiptDate = $receiptDate;
        return $this;
    }

    public function getAvailability(): ?int
    {
        return $this->availability;
    }

    public function setAvailability(?int $availability): EquipmentDTO
    {
        $this->availability = $availability;
        return $this;
    }

    public function getDonator(): ?string
    {
        return $this->donator;
    }

    public function setDonator(?string $donator): EquipmentDTO
    {
        $this->donator = $donator;
        return $this;
    }

    public function setSubmitType(string $type): EquipmentDTO
    {
        $this->submitType = $type;
        return $this;
    }

    public function getSubmitType(): ?string
    {
        return $this->submitType;
    }

    public static function fromRequest(Request $request): self
    {
        $dto = new self();

        $dto->donator = $request->get('donator');
        $dto->description = $request->get('description');
        $dto->receiptDate = $request->get('receiptDate');
        $dto->availability = 1;
        $dto->submitType = $request->get('submitButton');

        return $dto;
    }
}