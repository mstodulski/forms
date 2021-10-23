<?php
namespace test\forms\helpers\forms;

use DateTime;
use mstodulski\forms\Form;
use mstodulski\forms\FormError;
use mstodulski\forms\types\DateTimeType;
use mstodulski\forms\types\FloatType;
use mstodulski\forms\types\TextType;

class InvoicePositionForm extends Form {

    public function __construct(object $entity = null, array $options = [])
    {
        parent::__construct($entity, $options);

        $this
            ->addField(
                'name',
                TextType::class,
                [
                    'label' => 'Nazwa produktu',
                ]
            )
            ->addField(
                'quantity',
                TextType::class,
                [
                    'label' => 'Ilość'
                ]
            )
            ->addField(
                'sendDate',
                DateTimeType::class,
                [
                    'label' => 'Data wysyłki',
                    'nullable' => true,
                    'validator' => function($value) {
                        $formError = null;
                        if (trim($value) != '') {
                            $dateTime = DateTime::createFromFormat('Y-m-d H:i:s', $value);
                            $dateTimeInPast = new DateTime();
                            $dateTimeInPast->modify('-1 month');

                            if ($dateTime > $dateTimeInPast) {
                                $formError = new FormError();
                                $formError->setCurrentValue($value);
                                $formError->setErrorMessage('Data nie może być późniejsza niż ' . $dateTimeInPast->format('Y-m-d H:i:s'));
                            }
                        }

                        return $formError;
                    }
                ]
            )
            ->addField(
                'price',
                FloatType::class,
                [
                    'label' => 'Cena',
                    'nullable' => false,
                ]
            )
        ;
    }
}