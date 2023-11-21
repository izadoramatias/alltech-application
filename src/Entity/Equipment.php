<?php

namespace App\Entity;

use App\Repository\EquipmentRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
#[ORM\Table(name: 'Equipments')]
#[ORM\Entity(repositoryClass: EquipmentRepository::class)]
class Equipment
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 45)]
    private ?string $description = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $receipt_date = null;

    #[ORM\Column(type: Types::SMALLINT)]
    private ?int $availability = null;

    #[ORM\ManyToOne(cascade: ['persist'], inversedBy: 'equipment')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Donator $donator = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getReceiptDate(): ?\DateTimeInterface
    {
        return $this->receipt_date;
    }

    public function setReceiptDate(\DateTimeInterface $receipt_date): static
    {
        $this->receipt_date = $receipt_date;

        return $this;
    }

    public function getAvailability(): ?int
    {
        return $this->availability;
    }

    public function setAvailability(int $availability): static
    {
        $this->availability = $availability;

        return $this;
    }

    public function getDonator(): ?Donator
    {
        return $this->donator;
    }

    public function setDonator(?Donator $donator): static
    {
        $this->donator = $donator;

        return $this;
    }
}
