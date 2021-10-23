<?php

namespace app\admin\entities;

use DateTime;

class Invoice
{
    private ?int $id = null;
    private ?string $invoiceNumber = null;
    private ?DateTime $dateTime = null;
    private ?Customer $customer = null;
    private array $positions = [];
    private array $warehouseDocuments = [];
    private array $categories = [];
    private ?int $paymentDays = null;
    private ?string $notices = null;
    private ?Customer $payer = null;
    private ?InvoiceCategory $mainCategory = null;
    private array $files = [];
    private ?string $mysteryValue = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(?int $id): void
    {
        $this->id = $id;
    }

    public function setInvoiceNumber(?string $invoiceNumber): void
    {
        $this->invoiceNumber = $invoiceNumber;
    }

    public function getInvoiceNumber(): ?string
    {
        return $this->invoiceNumber;
    }

    public function getDateTime(): ?DateTime
    {
        return $this->dateTime;
    }

    public function setDateTime(?DateTime $dateTime): void
    {
        $this->dateTime = $dateTime;
    }

    public function setPositions(array $positions): void
    {
        $this->positions = $positions;
    }

    public function getPositions(): array
    {
        return $this->positions;
    }

    public function getCustomer(): ?Customer
    {
        return $this->customer;
    }

    public function setCustomer(?Customer $customer): void
    {
        $this->customer = $customer;
    }

    public function getWarehouseDocuments(): array
    {
        return $this->warehouseDocuments;
    }

    public function setWarehouseDocuments(array $warehouseDocuments): void
    {
        /** @var WarehouseDocument $warehouseDocument */
        foreach ($warehouseDocuments as $warehouseDocument) {
            $warehouseDocument->setInvoice($this);
        }

        $this->warehouseDocuments = $warehouseDocuments;
    }

    public function getCategories(): array
    {
        return $this->categories;
    }

    public function setCategories(array $categories): void
    {
        /** @var InvoiceCategory $category */
        foreach ($categories as $category) {
            $category->setInvoice($this);
        }

        $this->categories = $categories;
    }

    public function getPaymentDays(): ?int
    {
        return $this->paymentDays;
    }

    public function setPaymentDays(?int $paymentDays): void
    {
        $this->paymentDays = $paymentDays;
    }

    public function getNotices(): ?string
    {
        return $this->notices;
    }

    public function setNotices(?string $notices): void
    {
        $this->notices = $notices;
    }

    public function getPayer(): ?Customer
    {
        return $this->payer;
    }

    public function setPayer(?Customer $payer): void
    {
        $this->payer = $payer;
    }

    public function getMainCategory(): ?InvoiceCategory
    {
        return $this->mainCategory;
    }

    public function setMainCategory(?InvoiceCategory $mainCategory): void
    {
        $this->mainCategory = $mainCategory;
    }

    public function getFiles(): array
    {
        return $this->files;
    }

    public function setFiles(array $files): void
    {
        /** @var InvoiceFile $file */
        foreach ($files as $file) {
            $file->setInvoice($this);
        }

        $this->files = $files;
    }

    public function getMysteryValue(): ?string
    {
        return $this->mysteryValue;
    }

    public function setMysteryValue(?string $mysteryValue): void
    {
        $this->mysteryValue = $mysteryValue;
    }

}