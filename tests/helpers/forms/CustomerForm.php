<?php
namespace app\admin\forms;

use app\admin\entities\User;
use Exception;
use mstodulski\forms\Form;
use mstodulski\forms\types\DateTimeType;
use mstodulski\forms\types\TextType;

class CustomerForm extends Form {

    /** @throws Exception */
    public function __construct(object $entity = null)
    {
        parent::__construct($entity);

        $this
            ->addField(
                'name',
                TextType::class,
                [
                    'label' => 'Nazwa kontrahenta',
                ]
            )
            ->addField(
                'birthDate',
                DateTimeType::class,
                [
                    'label' => 'Data urodzenia'
                ]
            )
            ->addField(
                'user',
                UserForm::class,
                [
                    'class' => User::class,
                    'label' => 'Użytkownik'
                ]
            )
        ;
    }
}