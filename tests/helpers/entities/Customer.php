<?php

namespace test\forms\helpers\entities;

use DateTime;

class Customer
{
    private ?int $id = null;
    private ?string $name = null;
    private ?DateTime $birthDate = null;
    private ?User $user = null;

    public function setName(?string $name): void
    {
        $this->name = $name;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(?int $id): void
    {
        $this->id = $id;
    }

    public function getBirthDate(): ?DateTime
    {
        return $this->birthDate;
    }

    public function setBirthDate(?DateTime $birthDate): void
    {
        $this->birthDate = $birthDate;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): void
    {
        $this->user = $user;
    }
}