<?php
namespace test\forms\helpers\forms;

use test\forms\helpers\entities\Customer;
use test\forms\helpers\entities\InvoiceCategory;
use test\forms\helpers\entities\InvoicePosition;
use test\forms\helpers\entities\WarehouseDocument;
use test\forms\helpers\services\FileService;
use Exception;
use mstodulski\forms\Form;
use mstodulski\forms\types\CheckboxType;
use mstodulski\forms\types\CollectionType;
use mstodulski\forms\types\DateTimeType;
use mstodulski\forms\types\FileType;
use mstodulski\forms\types\HiddenType;
use mstodulski\forms\types\IntegerType;
use mstodulski\forms\types\MultiSelectType;
use mstodulski\forms\types\RadioButtonType;
use mstodulski\forms\types\SelectType;
use mstodulski\forms\types\SubmitType;
use mstodulski\forms\types\TextAreaType;
use mstodulski\forms\types\TextType;

class InvoiceForm extends Form {

    /** @throws Exception */
    public function __construct(object $entity = null, array $options = [])
    {
        parent::__construct($entity, $options);

        $this
            ->addField(
                'invoiceNumber',
                TextType::class,
                [
                    'label' => 'Numer faktury',
                ]
            )
            ->addField(
                'dateTime',
                DateTimeType::class,
                [
                    'label' => 'Data i czas wystawienia',
                    'template' => 'exampleDateField.tpl'
                ]
            )
            ->addField(
                'positions',
                CollectionType::class,
                [
                    'class' => InvoicePositionForm::class,
                    'entityClass' => InvoicePosition::class,
                    'label' => 'Pozycje',
                    'template' => 'positions.tpl'
                ]
            )
            ->addField(
                'customer',
                CustomerForm::class,
                [
                    'class' => Customer::class,
                    'label' => 'Kontrahent'
                ]
            )
            ->addField(
                'warehouseDocuments',
                MultiSelectType::class,
                [
                    'class' => WarehouseDocument::class,
                    'label' => 'Dokumenty magazynowe',
                ]
            )
            ->addField(
                'categories',
                CheckboxType::class,
                [
                    'class' => InvoiceCategory::class,
                    'label' => 'Kategorie faktury',
                ]
            )
            ->addField(
                'paymentDays',
                IntegerType::class,
                [
                    'label' => 'Dni na płatność',
                ]
            )
            ->addField(
                'notices',
                TextAreaType::class,
                [
                    'label' => 'Notatki',
                ]
            )
            ->addField(
                'payer',
                SelectType::class,
                [
                    'label' => 'Płatnik',
                    'class' => Customer::class,
                ]
            )
            ->addField(
                'mainCategory',
                RadioButtonType::class,
                [
                    'label' => 'Kategoria główna',
                    'class' => InvoiceCategory::class,
                    'choices' => function() {
                        $choices[222] = 'Kategoria 1';
                        $choices[444] = 'Kategoria 3';

                        return $choices;
                    },
                ]
            )
            ->addField(
                'files',
                FileType::class,
                [
                    'label' => 'Pliki',
                    'nullable' => true,
                    'transform' => function($data) {
                        return FileService::uploadFilesAndCreateFilesArray($this->entity, $data);
                    }
                ]
            )
            ->addField(
                'mysteryValue',
                HiddenType::class,
                [
                    'label' => 'Tajemnicza wartość',
                    'nullable' => true,
                ]
            )
            ->addField(
                'submit',
                SubmitType::class,
                [
                    'label' => 'Zatwierdź formularz',
                    'template' => 'customSubmit.tpl'
                ]
            )
        ;
    }
}
