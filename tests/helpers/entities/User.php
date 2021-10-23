<?php

namespace app\admin\entities;

use DateTime;

class User
{
    private ?int $id = null;
    private ?string $login = null;
    private ?string $password = null;
    private ?DateTime $lastLogin = null;

    public function setId(?int $id): void
    {
        $this->id = $id;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLastLogin(): ?DateTime
    {
        return $this->lastLogin;
    }

    public function getLogin(): ?string
    {
        return $this->login;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setLastLogin(?DateTime $lastLogin): void
    {
        $this->lastLogin = $lastLogin;
    }

    public function setLogin(?string $login): void
    {
        $this->login = $login;
    }

    public function setPassword(?string $password): void
    {
        $this->password = $password;
    }


}