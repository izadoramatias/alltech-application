<?php

namespace App\Entity;

use App\Repository\CompletedOrderRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Table(name: 'completed_orders')]
#[ORM\Entity(repositoryClass: CompletedOrderRepository::class)]
class CompletedOrder
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $delivery_date = null;

    #[ORM\OneToMany(mappedBy: 'completedOrder', targetEntity: Equipment::class)]
    private Collection $Equipment;

    #[ORM\OneToOne(cascade: ['persist', 'remove'])]
    private ?Order $UserOrder = null;

    #[ORM\Column(length: 255)]
    private ?string $description = null;

    public function __construct()
    {
        $this->Equipment = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDeliveryDate(): ?\DateTimeInterface
    {
        return $this->delivery_date;
    }

    public function setDeliveryDate(\DateTimeInterface $delivery_date): static
    {
        $this->delivery_date = $delivery_date;

        return $this;
    }

    /**
     * @return Collection<int, Equipment>
     */
    public function getEquipment(): Collection
    {
        return $this->Equipment;
    }

    public function addEquipment(Equipment $equipment): static
    {
        if (!$this->Equipment->contains($equipment)) {
            $this->Equipment->add($equipment);
            $equipment->setCompletedOrder($this);
        }

        return $this;
    }

    public function removeEquipment(Equipment $equipment): static
    {
        if ($this->Equipment->removeElement($equipment)) {
            // set the owning side to null (unless already changed)
            if ($equipment->getCompletedOrder() === $this) {
                $equipment->setCompletedOrder(null);
            }
        }

        return $this;
    }

    public function getUserOrder(): ?Order
    {
        return $this->UserOrder;
    }

    public function setUserOrder(?Order $UserOrder): static
    {
        $this->UserOrder = $UserOrder;

        return $this;
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
}
