<?php

namespace app\admin\entities;

class InvoiceCategory
{
    private ?int $id = null;
    private ?Invoice $invoice = null;
    private ?string $name = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(?int $id): void
    {
        $this->id = $id;
    }

    public function setInvoice(?Invoice $invoice): void
    {
        $this->invoice = $invoice;
    }

    public function getInvoice(): ?Invoice
    {
        return $this->invoice;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): void
    {
        $this->name = $name;
    }

    public function __toString() : string
    {
        return $this->name;
    }
}
