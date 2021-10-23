<?php

namespace app\admin\entities;

class WarehouseDocument
{
    private ?int $id = null;
    private ?Invoice $invoice = null;
    private ?string $warehouseDocumentNumber = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(?int $id): void
    {
        $this->id = $id;
    }

    public function getInvoice(): ?Invoice
    {
        return $this->invoice;
    }

    public function setInvoice(?Invoice $invoice): void
    {
        $this->invoice = $invoice;
    }

    public function setWarehouseDocumentNumber(?string $warehouseDocumentNumber): void
    {
        $this->warehouseDocumentNumber = $warehouseDocumentNumber;
    }

    public function getWarehouseDocumentNumber(): ?string
    {
        return $this->warehouseDocumentNumber;
    }

    public function __toString() : string
    {
        return $this->warehouseDocumentNumber;
    }

}