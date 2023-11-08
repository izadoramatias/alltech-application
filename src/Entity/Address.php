<?php

namespace App\Entity;

use App\Repository\AddressRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: AddressRepository::class)]
//#[ORM\Table(name: 'Addresses')]
class Address
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 16)]
    private ?string $zip_code = null;

    #[ORM\Column(length: 20)]
    private ?string $city = null;

    #[ORM\Column(length: 20)]
    private ?string $district = null;

    #[ORM\Column(length: 20)]
    private ?string $street = null;

    #[ORM\Column(length: 8)]
    private ?string $number = null;

    #[ORM\OneToOne(mappedBy: 'address_id', cascade: ['persist', 'remove'])]
    private ?Order $user_order = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getZipCode(): ?string
    {
        return $this->zip_code;
    }

    public function setZipCode(string $zip_code): static
    {
        $this->zip_code = $zip_code;

        return $this;
    }

    public function getCity(): ?string
    {
        return $this->city;
    }

    public function setCity(string $city): static
    {
        $this->city = $city;

        return $this;
    }

    public function getDistrict(): ?string
    {
        return $this->district;
    }

    public function setDistrict(string $district): static
    {
        $this->district = $district;

        return $this;
    }

    public function getStreet(): ?string
    {
        return $this->street;
    }

    public function setStreet(string $street): static
    {
        $this->street = $street;

        return $this;
    }

    public function getNumber(): ?string
    {
        return $this->number;
    }

    public function setNumber(string $number): static
    {
        $this->number = $number;

        return $this;
    }

    public function getUserOrder(): ?Order
    {
        return $this->user_order;
    }

    public function setUserOrder(Order $user_order): static
    {
        // set the owning side of the relation if necessary
        if ($user_order->getAddressId() !== $this) {
            $user_order->setAddressId($this);
        }

        $this->user_order = $user_order;

        return $this;
    }
}
