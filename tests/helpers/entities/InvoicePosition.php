<?php

namespace test\forms\helpers\entities;

use DateTime;

class InvoicePosition
{
    private ?int $id = null;
    private ?string $name = null;
    private ?float $quantity;
    private ?DateTime $sendDate = null;
    private ?float $price = null;

    public function setId(?int $id): void
    {
        $this->id = $id;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): void
    {
        $this->name = $name;
    }

    public function getQuantity(): ?float
    {
        return $this->quantity;
    }

    public function setQuantity(?float $quantity): void
    {
        $this->quantity = $quantity;
    }

    public function setSendDate(?DateTime $sendDate): void
    {
        $this->sendDate = $sendDate;
    }

    public function getSendDate(): ?DateTime
    {
        return $this->sendDate;
    }

    public function getPrice(): ?float
    {
        return $this->price;
    }

    public function setPrice(?float $price): void
    {
        $this->price = $price;
    }
}
