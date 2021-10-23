<?php
namespace test\forms\helpers\forms;

use mstodulski\forms\Form;
use mstodulski\forms\types\DateTimeType;
use mstodulski\forms\types\PasswordType;
use mstodulski\forms\types\TextType;

class UserForm extends Form {

    public function __construct(object $entity = null)
    {
        parent::__construct($entity);

        $this
            ->addField(
                'login',
                TextType::class,
                [
                    'label' => 'Login',
                ]
            )
            ->addField(
                'password',
                PasswordType::class,
                [
                    'label' => 'Hasło',
                    'transform' => function($value) {
                        return md5($value);
                    }
                ]
            )
            ->addField(
                'lastLogin',
                DateTimeType::class,
                [
                    'label' => 'Data ostatniego logowania'
                ]
            )
        ;
    }
}