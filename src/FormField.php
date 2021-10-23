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

class FormField
{
    private string $fieldName;
    private string $type;
    private array $options;
    private mixed $formValue = null;
    private mixed $value = null;
    private array $fields = [];
    private ?FormError $error = null;

    public function __construct(string $fieldName, string $type, array $options = [])
    {
        $this->fieldName = $fieldName;
        $this->type = $type;
        $this->options = $options;
    }

    public function getFieldName(): string
    {
        return $this->fieldName;
    }

    public function getOptions(): array
    {
        return $this->options;
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function setValue(mixed $value): void
    {
        $this->value = $value;
    }

    public function getValue(): mixed
    {
        return $this->value;
    }

    public function setFormValue(string|array $formValue = null): void
    {
        $this->formValue = $formValue;
    }

    public function getFormValue(): mixed
    {
        return $this->formValue;
    }

    public function getFields(): array
    {
        return $this->fields;
    }

    public function getField(string $fieldName)
    {
        return $this->fields[$fieldName];
    }

    public function setFields(array $fields): void
    {
        $this->fields = $fields;
    }

    public function setError(?FormError $error): void
    {
        $this->error = $error;
    }

    public function getError(): ?FormError
    {
        return $this->error;
    }
}
