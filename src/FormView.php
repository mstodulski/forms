<?php
/**
 * This file is part of the EasyCore package.
 *
 * (c) Marcin Stodulski <marcin.stodulski@devsprint.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace mstodulski\forms;

class FormView
{
    protected ?string $name = null;
    protected ?string $id = null;
    protected ?string $type = null;
    protected mixed $value = null;
    protected array $options = [];
    protected array $fields = [];
    protected array $path = [];
    protected ?FormError $error = null;

    public function setName(?string $name): void
    {
        $this->name = $name;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function getId(): ?string
    {
        return $this->id;
    }

    public function setId(?string $id): void
    {
        $this->id = $id;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(?string $type): void
    {
        $this->type = $type;
    }

    public function getValue(): mixed
    {
        return $this->value;
    }

    public function setValue(mixed $value): void
    {
        $this->value = $value;
    }

    public function getOptions(): array
    {
        return $this->options;
    }

    public function setOptions(array $options): void
    {
        $this->options = $options;
    }

    public function getFields(): array
    {
        return $this->fields;
    }

    public function getField(string $fieldName) : ?FormView
    {
        return $this->fields[$fieldName] ?? null;
    }

    public function addField(string $fieldName, self|array $field) : self
    {
        $this->fields[$fieldName] = $field;
        return $this;
    }

    public function setPath(array $path): void
    {
        $this->path = $path;
    }

    public function getPath(): array
    {
        return $this->path;
    }

    public function getError(): ?FormError
    {
        return $this->error;
    }

    public function setError(?FormError $error): void
    {
        $this->error = $error;
    }
}
