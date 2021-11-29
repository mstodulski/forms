<?php
namespace test\forms\helpers;

use mstodulski\forms\FormField;
use test\forms\helpers\entities\Customer;
use test\forms\helpers\entities\InvoiceCategory;
use test\forms\helpers\entities\WarehouseDocument;
use Exception;
use Throwable;

class TestDbBridge
{
    /** @throws Exception */
    public function transform(FormField $formField, bool $singleValue, mixed $value, object $parent = null) : array|object|null
    {
        if (!isset($formField->getOptions()['class'])) {
            throw new Exception('Form field ' . $formField->getFieldName() . ' must have a "class" option.');
        }

        switch ($formField->getOptions()['class']) {
            case WarehouseDocument::class:
                if (is_array($value)) {
                    return $this->getWarehouseDocuments($value);
                } else {
                    return $this->getWarehouseDocument($value);
                }

            case InvoiceCategory::class:

                if (is_array($value)) {
                    return $this->getInvoiceCategories($value);
                } else {
                    return $this->getInvoiceCategory($value);
                }

            case Customer::class:

                if (is_array($value)) {
                    return $this->getCustomers($value);
                } else {
                    return $this->getCustomer($value);
                }

            default:
                die('oprogramuj');
        }
    }

    public function reverse(FormField $formField, bool $singleValue, mixed $value, object $parent = null) : mixed
    {
        return ($value === null) ? null : $value->getId();
    }

    public function choices($objectClass) : array
    {
        $choices = [];

        switch ($objectClass) {
            case WarehouseDocument::class:
                $choices[5] = 'PZ-1/01/2021';
                $choices[6] = 'PZ-2/01/2021';
                $choices[7] = 'PZ-3/01/2021';
                $choices[8] = 'PZ-4/01/2021';
                break;

            case InvoiceCategory::class:
                $choices[222] = 'Kategoria 1';
                $choices[333] = 'Kategoria 2';
                $choices[444] = 'Kategoria 3';
                break;

            case Customer::class:
                $choices[4] = 'Kontrahent 1';
                $choices[5] = 'Kontrahent 2';
                $choices[6] = 'Kontrahent 3';
                break;
        }

        return $choices;
    }

    private function getWarehouseDocuments(array $ids) : array
    {
        $warehouseDocuments = [];

        if (in_array(5, $ids)) {
            $warehouseDocument = new WarehouseDocument();
            $warehouseDocument->setId(5);
            $warehouseDocument->setWarehouseDocumentNumber('PZ-1/01/2021');
            $warehouseDocuments[] = $warehouseDocument;
        }

        if (in_array(6, $ids)) {
            $warehouseDocument = new WarehouseDocument();
            $warehouseDocument->setId(6);
            $warehouseDocument->setWarehouseDocumentNumber('PZ-2/01/2021');
            $warehouseDocuments[] = $warehouseDocument;
        }

        if (in_array(7, $ids)) {
            $warehouseDocument = new WarehouseDocument();
            $warehouseDocument->setId(7);
            $warehouseDocument->setWarehouseDocumentNumber('PZ-3/01/2021');
            $warehouseDocuments[] = $warehouseDocument;
        }

        if (in_array(8, $ids)) {
            $warehouseDocument = new WarehouseDocument();
            $warehouseDocument->setId(8);
            $warehouseDocument->setWarehouseDocumentNumber('PZ-4/01/2021');
            $warehouseDocuments[] = $warehouseDocument;
        }

        return $warehouseDocuments;
    }

    private function getWarehouseDocument($id) : ?WarehouseDocument
    {
        $warehouseDocument = null;

        if ($id === 5) {
            $warehouseDocument = new WarehouseDocument();
            $warehouseDocument->setId(5);
            $warehouseDocument->setWarehouseDocumentNumber('PZ-1/01/2021');
        }

        if ($id === 6) {
            $warehouseDocument = new WarehouseDocument();
            $warehouseDocument->setId(6);
            $warehouseDocument->setWarehouseDocumentNumber('PZ-2/01/2021');
        }

        if ($id === 7) {
            $warehouseDocument = new WarehouseDocument();
            $warehouseDocument->setId(7);
            $warehouseDocument->setWarehouseDocumentNumber('PZ-3/01/2021');
        }

        if ($id === 8) {
            $warehouseDocument = new WarehouseDocument();
            $warehouseDocument->setId(8);
            $warehouseDocument->setWarehouseDocumentNumber('PZ-4/01/2021');
        }

        return $warehouseDocument;
    }

    private function getInvoiceCategories(array $ids) : array
    {
        $categories = [];

        if (in_array(222, $ids)) {
            $category = new InvoiceCategory();
            $category->setId(222);
            $category->setName('Kategoria 1');
            $categories[] = $category;
        }

        if (in_array(333, $ids)) {
            $category = new InvoiceCategory();
            $category->setId(333);
            $category->setName('Kategoria 2');
            $categories[] = $category;
        }

        if (in_array(444, $ids)) {
            $category = new InvoiceCategory();
            $category->setId(444);
            $category->setName('Kategoria 3');
            $categories[] = $category;
        }

        return $categories;
    }

    private function getInvoiceCategory($id) : ?InvoiceCategory
    {
        $chosenCategory = null;

        switch ($id) {
            case '222':
                $chosenCategory = new InvoiceCategory();
                $chosenCategory->setId(222);
                $chosenCategory->setName('Kategoria 1');
                break;
            case '333':
                $chosenCategory = new InvoiceCategory();
                $chosenCategory->setId(333);
                $chosenCategory->setName('Kategoria 2');
                break;
            case '444':
                $chosenCategory = new InvoiceCategory();
                $chosenCategory->setId(444);
                $chosenCategory->setName('Kategoria 3');
                break;
        }

        return $chosenCategory;
    }

    public function getCustomers(array $ids) : array
    {
        $customers = [];

        if (in_array(222, $ids)) {
            $customer = new Customer();
            $customer->setId(4);
            $customer->setName('Kontrahent 1');
            $customers[] = $customer;
        }

        if (in_array(333, $ids)) {
            $customer = new Customer();
            $customer->setId(5);
            $customer->setName('Kontrahent 2');
            $customers[] = $customer;
        }

        if (in_array(444, $ids)) {
            $customer = new Customer();
            $customer->setId(6);
            $customer->setName('Kontrahent 3');
            $customers[] = $customer;
        }

        return $customers;
    }

    public function getCustomer($id) : ?Customer
    {
        $customer = null;

        switch ($id) {
            case '4':
                $customer = new Customer();
                $customer->setId(4);
                $customer->setName('Kontrahent 1');
                break;
            case '5':
                $customer = new Customer();
                $customer->setId(5);
                $customer->setName('Kontrahent 2');
                break;
            case '6':
                $customer = new Customer();
                $customer->setId(6);
                $customer->setName('Kontrahent 3');
                break;

        }

        return $customer;
    }
}
