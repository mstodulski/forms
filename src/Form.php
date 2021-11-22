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

use Exception;
use Iterator;
use JetBrains\PhpStorm\Pure;
use mstodulski\forms\types\CollectionFieldTypeInterface;
use mstodulski\forms\types\EntityFieldTypeInterface;
use mstodulski\forms\types\FileTypeInterface;
use mstodulski\forms\types\NoValueFieldTypeInterface;
use mstodulski\forms\types\PasswordFieldTypeInterface;
use mstodulski\forms\types\SimpleFieldTypeInterface;

class Form
{
    protected object $entity;
    protected string $formName;
    private array $fields = [];
    private array $errorsForFields = [];
    private ?object $dbBridge = null;

    private const ERROR_SOURCE_FORM_FIELD = 'formField';
    private const ERROR_SOURCE_FORM_ERRORS = 'formErrorFields';

    const defaultFieldOptions = [
        'form' => null, //klasa formularza, cały formularz jest wstawiany w miejsce pola
        'class' => null, // klasa encji obsługiwana przez pole
        'entityClass' => null, //klasa encji obsługiwanej przez formularz
        'label' => null, // etykieta pola
        'choices' => null, //callable, możliwości wyboru w danym polu
        'hint' => null, //podpowiedź do pola
        'validator' => null, //callable, walidator indywidualny do pola (nadrzędny w stosunku do domyślnego walidatora w klasie typu pola)
        'transform' => null, //callable, indywidualna funkcja do tworzenia z wartości wpisanej w formularzu wartości, która ma być wstawiona do encji, powinna zwracać tę wartość
        'reverse' => null, //callable, indywidualna funkcja do tworzenia wartości z encji, która ma być wstawiona w polu formularza, powinna zwracać tę wartość
        'template' => null, //ścieżka do szablonu reprezentującego dany wiersz formularza (jeśli pole form jest zdefiniowane)
        'nullable' => false, //czy wartość może być pusta
        'mapped' => true, // czy wartość ma być zapisania do encji, jeśli dane są poprawne
    ];

    public function __construct(object $entity = null, array $options = [])
    {
        if (isset($entity)) $this->entity = $entity;
        $path = explode('\\',  get_class($this));
        $formName = strtolower(array_pop($path));
        if (str_ends_with($formName, 'form')) {
            $formName = substr($formName, 0, mb_strlen($formName) - 4);
        }

        $this->formName = ($formName != '') ? $formName : 'form';

        if (isset($options['dbBridge'])) $this->dbBridge = $options['dbBridge'];
    }

    /** @throws Exception */
    public function addField(string $fieldName, string $type, array $options = []) : self
    {
        $interfaces = class_implements($type);
        /** @var SimpleFieldTypeInterface $typeObject */
        if (isset($options['mapped']) && ($options['mapped'] === false)) {
            $typeObject = new $type(null);
        } else {
            $typeObject = new $type(isset($this->entity) ? $this->getValueFromEntity($this->entity, $fieldName, $type, $options) : null);
        }

        foreach (self::defaultFieldOptions as $option => $defaultValue) {
            if (!isset($options[$option])) $options[$option] = $defaultValue;
        }

        if (isset($interfaces[SimpleFieldTypeInterface::class]) || isset($interfaces[FileTypeInterface::class]) || isset($interfaces[PasswordFieldTypeInterface::class])) {
            //pojedyncze pole reprezentujące jedną wartość w formularzu
            $formField = new FormField($fieldName, $type, $options);
            $formField->setValue(isset($this->entity) ? $this->getValueFromEntity($this->entity, $fieldName, $type, $options): null);

            if (isset($this->entity)) {
                if (is_object($formField->getValue()) && is_subclass_of($formField->getValue(), Iterator::class)) {
                    $value = iterator_to_array($formField->getValue());
                } else {
                    $value = $formField->getValue();
                }

                if ($options['reverse'] !== null) {
                    $formField->setFormValue($options['reverse']($value));
                } elseif (($this->dbBridge !== null) && (isset($options['class']))) {
                    $formField->setFormValue($this->dbBridge->reverse($formField, true, $value));
                } else {
                    $formField->setFormValue($typeObject->reverse($value));
                }
            }

            $this->fields[$fieldName] = $formField;

        } elseif ($typeObject instanceof self) {
            //pojedynczy formularz
            $entity = (isset($this->entity)) ? $this->getValueFromEntity($this->entity, $fieldName, $type, $options) : null;
            /** @var self $form */
            $form = new $typeObject($entity);
            $this->fields[$fieldName] = $form;

        } elseif (isset($interfaces[CollectionFieldTypeInterface::class])) {
            $formField = new FormField($fieldName, $type, $options);
            if (isset($options['mapped']) && ($options['mapped'] === false)) {
                $values = [];
            } else {
                $values = (isset($this->entity)) ? $this->getValueFromEntity($this->entity, $fieldName, $type, $options) : [];
            }

            $typeObject = new $type();
            $values = $typeObject->reverse($values);

            if ((isset($options['class'])) && (is_subclass_of($options['class'], self::class))) {
                //kolekcja formularzy
                $formFieldValues = [];
                foreach ($values as $key => $value) {
                    /** @var self $form */
                    $form = new $options['class']();
                    /** @var FormField $field */
                    foreach ($form->getFields() as $field) {
                        $typeForField = $field->getType();
                        /** @var SimpleFieldTypeInterface $typeObject */
                        $typeForFieldObject = new $typeForField();

                        $field->setValue($this->getValueFromEntity($value, $field->getFieldName(), $typeForField, $field->getOptions()));
                        $field->setFormValue($typeForFieldObject->reverse($this->getValueFromEntity($value, $field->getFieldName(), $typeForField, $field->getOptions())));

                        $formFieldValues[$key][$field->getFieldName()] = $field;
                    }
                }

                $formField->setFields($formFieldValues);
            } else {
                //kolekcja konkretnych wartości
                $formField = new FormField($fieldName, $type, $options);

                $formValues = [];
                foreach ($values as $value) {
                    if (is_object($value)) {

                        if ($options['reverse'] !== null) {
                            $formValues[] = $options['reverse']($value);
                        } elseif (($this->dbBridge !== null) && (isset($options['class']))) {
                            $formValues[] = $this->dbBridge->reverse($formField, false, $value, $this->entity);
                        } else {
                            $formValues[] = $typeObject->reverse($value);
                        }
                    } else {
                        $formValues[] = $value;
                    }
                }

                $formField->setValue($formValues);
                $formField->setFormValue($formValues);
                $this->fields[$fieldName] = $formField;
            }

            $this->fields[$fieldName] = $formField;

//        } elseif(isset($interfaces[EntityFieldTypeInterface::class])) {
//            if (is_subclass_of($options['class'], self::class)) {
//                //formularz reprezentujący pojedynczą encję
//                /** @var self $form */
//                $form = new $typeObject();
//                /** @var FormField $field */
//                foreach ($form->getFields() as $field) {
//                    $formField = new FormField($field->getFieldName(), $field->getType(), $field->getOptions());
//
//                    if (isset($this->entity)) {
//                        $formField->setValue($this->getValueFromEntity($this->entity, $field->getFieldName(), $field->getType(), $field->getOptions()));
//                        $formField->setFormValue($this->getValueFromEntity($this->entity, $field->getFieldName(), $field->getType(), $field->getOptions()));
//                    }
//
//                    $this->fields[$fieldName][$field->getFieldName()] = $formField;
//                }
//            } else {
//                //id encji
//                $formField = new FormField($fieldName, $type, $options);
//                if (isset($this->entity)) {
//                    $formField->setValue((string)$this->entity);
//                    $formField->setFormValue($this->getValueFromEntity($this->entity, 'id', $type, $options));
//                }
//
//                $this->fields[$fieldName] = $formField;
//            }
        } elseif (isset($interfaces[NoValueFieldTypeInterface::class])) {
            //pojedyncze pole nieprzekazujące wartości (przycisk na przykład)
            $formField = new FormField($fieldName, $type, $options);
            $this->fields[$fieldName] = $formField;
        } else {
            throw new Exception('Unknown pair - field type with field class.');
        }

        return $this;
    }

    public function createFormView(?array $path = []) : FormView
    {
        $formView = new FormView();
        $formView->setName($this->formName);
        $formView->setId($this->formName);
        $formView->setType('form');

        if (empty($path)) {
            $path[] = $this->formName;
        }

        foreach ($this->getFields() as $fieldName => $formField) {
            if ($formField instanceof FormField) {

                $fieldType = $formField->getType();
                $subformView = new FormView();

                /** @var SimpleFieldTypeInterface|string $fieldType */
                switch ($fieldType::getAlias()) {
                    case 'collectionType':
                        $subformRows = [];
                        $subformView->setType('collection');
                        $subformView->setOptions($formField->getOptions());
                        foreach ($formField->getFields() as $index => $field) {
                            $newFormView = new FormView();
                            foreach ($field as $singleFieldName => $singleField) {
                                $singleFieldType = $singleField->getType();
                                $singleSubformView = new FormView();
                                $this->defineValuesForSubformView($singleSubformView, $singleFieldType, $singleField, $path, [$fieldName, $index, $singleFieldName], self::ERROR_SOURCE_FORM_ERRORS);
                                $newFormView->addField($singleFieldName, $singleSubformView);
                            }

                            $subformRows[$index] = $newFormView;
                        }

                        foreach ($subformRows as $index => $subformRow) {
                            $subformView->addField($index, $subformRow);
                        }

                        break;
                    default:
                        $this->defineValuesForSubformView($subformView, $fieldType, $formField, $path, [$fieldName], self::ERROR_SOURCE_FORM_FIELD);
                        break;
                }

                $formView->addField($fieldName, $subformView);

            } elseif ($formField instanceof self) {
                $formView->addField($fieldName, $formField->createFormView(array_merge($path, [$fieldName])));

            }
        }

        return $formView;
    }

    /** @throws Exception */
    public function processRequest($request) : bool
    {
        $requestFields = $request[$this->formName];

        /** @var FormField $formField */
        foreach ($this->getFields() as $fieldName => $formField) {
            if (!isset($requestFields[$fieldName]) && ($formField instanceof FormField)) {
                if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                    $interfaces = class_implements($formField->getType());
                    if (isset($interfaces[CollectionFieldTypeInterface::class])) {
                        $requestFields[$fieldName] = [];
                    } elseif (!isset($interfaces[NoValueFieldTypeInterface::class])) {
                        $requestFields[$fieldName] = null;
                    }
                } elseif ($_SERVER['REQUEST_METHOD'] === 'PUT') {
                    $requestFields[$fieldName] = $formField->getValue();
                }
            }
        }

        foreach ($requestFields as $requestFieldName => $requestFieldValue) {
            if (isset($this->fields[$requestFieldName])) {
                /** @var FormField $formField */
                $formField = $this->fields[$requestFieldName];
                if ($formField instanceof FormField) {
                    $interfaces = class_implements($formField->getType());
                    $options = $formField->getOptions();
                    if ((isset($interfaces[SimpleFieldTypeInterface::class]))) {
                        //dla pola reprezentującego pojedynczą wartość
                        $valid = $this->validateFormFieldAndSetValue($formField, $requestFieldValue);

                        if (isset($this->entity) && $valid && $options['mapped']) {
                            $setter = 'set' . ucfirst($formField->getFieldName());
                            if (!method_exists($this->entity, $setter)) throw new Exception('Class ' . get_class($this->entity) . ' must have a ' . $setter . '() method');

                            $typeObjectClass = $formField->getType();
                            /** @var SimpleFieldTypeInterface $typeObject */
                            $typeObject = new $typeObjectClass();

                            if ($options['transform'] !== null) {
                                //najpierw funkcja transform z pola formularza
                                $this->entity->$setter($options['transform']($formField->getFormValue()));
                            } elseif (($this->dbBridge !== null) && (isset($options['class']))) {
                                //potem funkcja transform z db bridge

                                $interfaces = class_implements($formField->getType());

                                $singleValue = true;
                                if (isset($interfaces[CollectionFieldTypeInterface::class])) {
                                    $singleValue = false;
                                }

                                $this->entity->$setter($this->dbBridge->transform($formField, $singleValue, $formField->getFormValue()));
                            } else {
                                //a na końcu próbujemy nadać wartość, jeśli nie jest zdefiniowana funkcja transform dla pola formularza oraz nie ma dbBridge
                                $this->entity->$setter($typeObject->transform($formField->getFormValue()));
                            }
                        }
                    } elseif (isset($interfaces[PasswordFieldTypeInterface::class])) {
                        //dla pola reprezentującego hasło
                        $valid = $this->validateFormFieldAndSetValue($formField, $requestFieldValue);
                        if ($requestFieldValue != '') {
                            if (isset($this->entity) && $valid && $options['mapped']) {
                                $setter = 'set' . ucfirst($formField->getFieldName());
                                if (!method_exists($this->entity, $setter)) throw new Exception('Class ' . get_class($this->entity) . ' must have a ' . $setter . '() method');

                                $typeObjectClass = $formField->getType();
                                /** @var SimpleFieldTypeInterface $typeObject */
                                $typeObject = new $typeObjectClass();

                                if ($options['transform'] !== null) {
                                    //najpierw funkcja transform z pola formularza
                                    $this->entity->$setter($options['transform']($formField->getFormValue()));
                                } elseif (($this->dbBridge !== null) && (isset($options['class']))) {
                                    //potem funkcja transform z db bridge
                                    $this->entity->$setter($this->dbBridge->transform($formField, true, $formField->getFormValue()));
                                } else {
                                    //a na końcu próbujemy nadać wartość, jeśli nie jest zdefiniowana funkcja transform dla pola formularza oraz nie ma dbBridge
                                    $this->entity->$setter($typeObject->transform($formField->getFormValue()));
                                }
                            }
                        }
                    } elseif ((isset($interfaces[CollectionFieldTypeInterface::class]))) {

                        $options = $formField->getOptions();


                        if (!isset($options['mapped']) || ($options['mapped'] === true)) {
                            if (isset($options['class'])) {
                                if (is_subclass_of($options['class'], self::class)) {
                                    //dla kolekcji formularzy
                                    $formFieldFields = $formField->getFields();
                                    $formFieldFieldsIndexes = array_flip(array_keys($formFieldFields));

                                    foreach (array_keys($requestFields[$formField->getFieldName()]) as $index) {
                                        if (!isset($formFieldFields[$index])) {
                                            $collectionForm = new $options['class']();
                                            $collectionForm->processRequest([$collectionForm->formName => $requestFields[$formField->getFieldName()][$index]]);
                                            $formFieldFields[$index] = $collectionForm->getFields();
                                        }
                                        unset($formFieldFieldsIndexes[$index]);
                                    }

                                    foreach ($formFieldFieldsIndexes as $indexToRemove) {
                                        if (isset($formFieldFields[$indexToRemove])) unset($formFieldFields[$indexToRemove]);
                                    }

                                    $formField->setFields($formFieldFields);

                                    if (isset($this->entity)) {
                                        $getter = 'get' . ucfirst($formField->getFieldName());
                                        $setter = 'set' . ucfirst($formField->getFieldName());
                                        if (!method_exists($this->entity, $getter)) throw new Exception('Class ' . get_class($this->entity) . ' must have a ' . $getter . '() method');
                                        $collection = $this->entity->$getter();

                                        $originalClass = null;
                                        if (is_object($collection) && is_subclass_of($collection, Iterator::class)) {
                                            $originalClass = get_class($collection);
                                            $collection = iterator_to_array($collection);
                                        }

                                        foreach ($formFieldFieldsIndexes as $indexToRemove) {
                                            if (isset($collection[$indexToRemove])) unset($collection[$indexToRemove]);
                                        }

                                        foreach (array_keys($requestFields[$formField->getFieldName()]) as $index) {
                                            if (!isset($collection[$index])) {
                                                $collection[$index] = new $options['entityClass']();
                                            }
                                        }

                                        if ($originalClass !== null) {
                                            $collectionObject = new $originalClass($formField->getOptions()['entityClass']);
                                            foreach ($collection as $element) {
                                                $collectionObject->add($element);
                                            }
                                            $collection = $collectionObject;
                                        }

                                        $this->entity->$setter($collection);

                                        if (!is_iterable($collection)) throw new Exception('Value must be iterable.');
                                        if (is_object($collection) && is_subclass_of($collection, Iterator::class)) $collection = iterator_to_array($collection);
                                    }

                                    foreach ($formField->getFields() as $fieldIndex => $fields) {
                                        /** @var FormField $collectionField */
                                        foreach ($fields as $collectionFieldName => $collectionField) {
                                            $collectionRequestFieldValue = $requestFieldValue[$fieldIndex][$collectionFieldName];
                                            $valid = $this->validateFormFieldAndSetValue($collectionField, $collectionRequestFieldValue);

                                            if ($valid && isset($collection)) {
                                                $setter = 'set' . ucfirst($collectionFieldName);
                                                if (!method_exists($collection[$fieldIndex], $setter)) throw new Exception('Class ' . get_class($collection[$fieldIndex]) . ' must have a ' . $setter . '() method');
                                                $collection[$fieldIndex]->$setter($collectionField->getValue());
                                            }
                                        }
                                    }
                                } else {
                                    //dla kolekcji czegoś innego
                                    $valid = $this->validateFormFieldAndSetValue($formField, $requestFieldValue);

                                    if (isset($this->entity) && $valid) {
                                        if ($options['transform'] !== null) {
                                            $value = $options['transform']($requestFieldValue);
                                        } elseif ($this->dbBridge !== null) {
                                            $value = $this->dbBridge->transform($formField, false, $requestFieldValue, $this->entity);
                                        } else {
                                            throw new Exception('Field ' . $requestFieldName . ' must have a "transform" option, or DBBridge must be defined.');
                                        }

                                        $setter = 'set' . ucfirst($formField->getFieldName());
                                        if (method_exists($this->entity, $setter)) {
                                            $this->entity->$setter($value);
                                        } else {
                                            throw new Exception('Class ' . get_class($this->entity) . ' must have a "' . $setter . '" method.');
                                        }
                                    }
                                }
                            } else {
                                throw new Exception('Field ' . $requestFieldName . ' must have a "class" option.');
                            }
                        }
                    } elseif ((isset($interfaces[FileTypeInterface::class]))) {
                        $files = $_FILES[$this->formName] ?? [];

                        $typeObjectClass = $formField->getType();
                        /** @var SimpleFieldTypeInterface $typeObject */
                        $typeObject = new $typeObjectClass();
                        $fileArray = [];

                        if (!empty($files)) {
                            foreach ($files['name'][$formField->getFieldName()] as $key => $fileName) {
                                if (trim($fileName) != '') {
                                    $fileRow['name'] = $fileName;
                                    $fileRow['type'] = $files['type'][$formField->getFieldName()][$key];
                                    $fileRow['tmp_name'] = $files['tmp_name'][$formField->getFieldName()][$key];
                                    $fileRow['error'] = $files['error'][$formField->getFieldName()][$key];
                                    $fileRow['size'] = $files['size'][$formField->getFieldName()][$key];

                                    $fileArray[] = $fileRow;
                                }
                            }

                            if (empty($fileArray)) $fileArray = null;

                            $valid = $this->validateFormFieldAndSetValue($formField, $fileArray);

                            if (isset($this->entity) && $valid && $options['mapped']) {
                                $setter = 'set' . ucfirst($formField->getFieldName());
                                if (!method_exists($this->entity, $setter)) throw new Exception('Class ' . get_class($this->entity) . ' must have a ' . $setter . '() method');

                                if ($options['transform'] !== null) {
                                    //najpierw funkcja transform z pola formularza
                                    $transformedValue = $options['transform']($formField->getFormValue(), $options['nullable']);
                                } elseif (($this->dbBridge !== null) && (isset($options['class']))) {
                                    //potem funkcja transform z db bridge
                                    $transformedValue = $this->dbBridge->transform($formField, true, $formField->getFormValue());
                                } else {
                                    //a na końcu próbujemy nadać wartość, jeśli nie jest zdefiniowana funkcja transform dla pola formularza oraz nie ma dbBridge
                                    $transformedValue = $typeObject->transform($formField->getFormValue(), $options['nullable']);
                                }

                                if ($transformedValue instanceof FormError) {
                                    $formField->setError($transformedValue);
                                } else {
                                    $this->entity->$setter($transformedValue);
                                }
                            } elseif (!$valid) {
                                die('no valid');
                            }
                        } elseif (!$options['nullable']) {
                            $transformedValue = $typeObject->validate(null, $options['nullable']);

                            if ($transformedValue instanceof FormError) {
                                $formField->setError($transformedValue);
                            }
                        }
                    }
                } elseif ($formField instanceof self) {
                    $formField->processRequest([$formField->formName => $requestFieldValue]);
                }
            }
        }

        $this->errorsForFields = $this->getErrorMessages();

        return empty($this->errorsForFields);
    }

    public function getErrorForField(string $fieldId)
    {
        return $this->errorsForFields[$fieldId] ?? null;
    }

    /**
     * @return array
     */
    public function getErrorsForFields(): array
    {
        return $this->errorsForFields;
    }

    public function getEntity() : ?object
    {
        return $this->entity ?? null;
    }

    #[Pure] private function buildIdForField(FormView $formView) : string
    {
        return implode('_', $formView->getPath());
    }

    private function validateFormFieldAndSetValue(FormField $formField, mixed $requestFieldValue) : bool
    {
        $formField->setFormValue($requestFieldValue);

        $typeObjectClass = $formField->getType();
        /** @var SimpleFieldTypeInterface $typeObject */
        $typeObject = new $typeObjectClass();

        if (($formField->getOptions()['validator'] !== null) && (is_callable($formField->getOptions()['validator']))) {
            $validationResult = $formField->getOptions()['validator']($requestFieldValue);
        } else {
            $validationResult = $typeObject->validate($requestFieldValue, $formField->getOptions()['nullable']);
        }

        if ($validationResult !== null) {
            $formField->setError($validationResult);
            return false;
        } else {
            $formField->setValue($typeObject->transform($formField->getFormValue()));
        }

        return true;
    }

    private function defineValuesForSubformView(FormView $subformView, SimpleFieldTypeInterface|string $fieldType, FormField $formField, array $path, array $thisFieldPath, string $getErrorFrom)
    {
        $subformView->setType($fieldType::getAlias());
        $subformView->setValue($formField->getFormValue());

        $options = $formField->getOptions();
        if (isset($options['choices'])) {
            $options['choices'] = $options['choices']();
        } elseif (($this->dbBridge !== null) && (isset($options['class']))) {
            $options['choices'] = $this->dbBridge->choices($options['class']);
        } else {
            $options['choices'] = [];
        }

        $subformView->setOptions($options);
        $subformView->setPath(array_merge($path, $thisFieldPath));
        $subformView->setName($this->getFieldFullName($subformView));
        $subformView->setId($this->buildIdForField($subformView));

        if ($getErrorFrom == self::ERROR_SOURCE_FORM_FIELD) {
            $subformView->setError($formField->getError());
        } elseif ($getErrorFrom == self::ERROR_SOURCE_FORM_ERRORS) {
            $subformView->setError($this->getErrorForField($this->buildIdForField($subformView)));
        }
    }

    /** @throws Exception */
    private function getErrorMessages() : array
    {
        $fields = $this->getFields();
        $errorsArray = [];
        return $this->getErrors($fields, $errorsArray, [$this->formName]);
    }

    /** @throws Exception */
    private function getErrors(array $fields, &$errorsArray = [], $path = [])
    {
        /** @var FormField $formField */
        foreach ($fields as $fieldName => $formField) {

            if ($formField instanceof FormField) {

                $interfaces = class_implements($formField->getType());

                if (isset($interfaces[CollectionFieldTypeInterface::class])) {
                    $options = $formField->getOptions();
                    if (!isset($options['mapped']) || $options['mapped'] === true) {
                        if (isset($options['class'])) {
                            if (is_subclass_of($options['class'], self::class)) {
                                //dla kolekcji formularzy
                                foreach ($formField->getFields() as $fieldIndex => $collectionFields) {
                                    $elementPath = array_merge($path, [$fieldName, $fieldIndex]);
                                    $this->getErrors($collectionFields, $errorsArray, $elementPath);
                                }
                            } else {
                                //dla kolekcji czegoś innego
                                if ($formField->getError() !== null) {
                                    $elementPath = array_merge($path, [$fieldName]);
                                    $errorsArray[implode('_', $elementPath)] = $formField->getError();
                                }
                            }
                        } else {
                            throw new Exception('Field ' . $fieldName . ' must have a "class" option.');
                        }
                    }
                } else {
                    if ($formField->getError() !== null) {
                        $elementPath = array_merge($path, [$fieldName]);
                        $errorsArray[implode('_', $elementPath)] = $formField->getError();
                    }
                }
            } elseif ($formField instanceof self) {
                $formFields = $formField->getFields();
                $elementPath = array_merge($path, [$fieldName]);
                $this->getErrors($formFields, $errorsArray, $elementPath);
            }
        }

        return $errorsArray;
    }

    public function getFields() : array
    {
        return $this->fields;
    }

    public function getField(string $fieldName) : FormField|self
    {
        return $this->fields[$fieldName];
    }

    public function getFormName() : string
    {
        return $this->formName;
    }

    private function getFieldFullName(FormView $formView) : string
    {
        $fieldPath = $formView->getPath();
        $formName = reset($fieldPath);
        $pathExceptFirst = array_slice($fieldPath, 1);

        return $formName . '[' . implode('][', $pathExceptFirst) . ']';
    }

    /** @throws Exception */
    private function getValueFromEntity(?object $entity, string $fieldName, string $fieldType, array $options) : mixed
    {
        if (!isset($entity)) {
            return null;
        }

        if (!isset($options['mapped']) || ($options['mapped'] === false)) {
            return null;
        }

        $getterName = 'get' . ucfirst($fieldName);
        if (method_exists($entity, $getterName)) {
            $value = $entity->$getterName();
        } else {
            $interfaces = class_implements($fieldType);
            if (!isset($interfaces[NoValueFieldTypeInterface::class])) {
                throw new Exception('Class ' . get_class($entity) . ' must have a ' . $getterName . ' method.');
            } else {
                $value = [];
            }
        }

        return $value;
    }
}
