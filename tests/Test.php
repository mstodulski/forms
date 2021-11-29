<?php

use test\forms\helpers\entities\Invoice;
use test\forms\helpers\entities\InvoiceCategory;
use test\forms\helpers\entities\WarehouseDocument;
use test\forms\helpers\forms\InvoiceForm;
use mstodulski\forms\FormError;
use mstodulski\forms\FormView;
use mstodulski\forms\types\FileType;
use mstodulski\forms\types\TextType;
use PHPUnit\Framework\TestCase;
use test\forms\helpers\TestDbBridge;

class Test extends TestCase
{
    public function testCreateFormViewWithoutEntity()
    {
        $invoiceForm = new InvoiceForm(null, [
            'dbBridge' => new TestDbBridge(),
        ]);

        $formView = $invoiceForm->createFormView();

        $this->assertEquals('invoice', $formView->getName());
        $this->assertEquals('invoice', $formView->getId());
        $this->assertEquals('form', $formView->getType());
        $this->assertNull($formView->getValue());
        $this->assertEmpty($formView->getOptions());
        $this->assertEmpty($formView->getPath());
        $this->assertNull($formView->getError());

        $formField = $formView->getField('invoiceNumber');
        $this->assertInstanceOf(FormView::class, $formField);
        $this->assertEquals('invoice[invoiceNumber]', $formField->getName());
        $this->assertEquals('invoice_invoiceNumber', $formField->getId());
        $this->assertEquals('textType', $formField->getType());
        $this->assertNull($formField->getValue());
        $this->assertEquals('Numer faktury', $formField->getOptions()['label']);
        $this->assertNull($formField->getOptions()['form']);
        $this->assertNull($formField->getOptions()['class']);
        $this->assertNull($formField->getOptions()['entityClass']);
        $this->assertEmpty($formField->getOptions()['choices']);
        $this->assertNull($formField->getOptions()['hint']);
        $this->assertNull($formField->getOptions()['validator']);
        $this->assertNull($formField->getOptions()['transform']);
        $this->assertNull($formField->getOptions()['reverse']);
        $this->assertNull($formField->getOptions()['template']);
        $this->assertFalse($formField->getOptions()['nullable']);
        $this->assertNull($formView->getError());

        $formField = $formView->getField('dateTime');
        $this->assertInstanceOf(FormView::class, $formField);
        $this->assertEquals('invoice[dateTime]', $formField->getName());
        $this->assertEquals('invoice_dateTime', $formField->getId());
        $this->assertEquals('dateTimeType', $formField->getType());
        $this->assertNull($formField->getValue());
        $this->assertEquals('Data i czas wystawienia', $formField->getOptions()['label']);
        $this->assertNull($formField->getOptions()['form']);
        $this->assertNull($formField->getOptions()['class']);
        $this->assertNull($formField->getOptions()['entityClass']);
        $this->assertEmpty($formField->getOptions()['choices']);
        $this->assertNull($formField->getOptions()['hint']);
        $this->assertNull($formField->getOptions()['validator']);
        $this->assertNull($formField->getOptions()['transform']);
        $this->assertNull($formField->getOptions()['reverse']);
        $this->assertEquals('exampleDateField.tpl', $formField->getOptions()['template']);
        $this->assertFalse($formField->getOptions()['nullable']);
        $this->assertNull($formView->getError());

        $formField = $formView->getField('positions');

        $this->assertNull($formField->getName());
        $this->assertNull($formField->getId());
        $this->assertEquals('collection', $formField->getType());
        $this->assertNull($formField->getValue());

        $this->assertEquals('test\forms\helpers\forms\InvoicePositionForm', $formField->getOptions()['class']);
        $this->assertEquals('test\forms\helpers\entities\InvoicePosition', $formField->getOptions()['entityClass']);
        $this->assertEquals('Pozycje', $formField->getOptions()['label']);
        $this->assertEquals('positions.tpl', $formField->getOptions()['template']);
        $this->assertTrue($formField->getOptions()['mapped']);
        $this->assertFalse($formField->getOptions()['nullable']);
        $this->assertNull($formField->getOptions()['form']);
        $this->assertNull($formField->getOptions()['choices']);
        $this->assertNull($formField->getOptions()['hint']);
        $this->assertNull($formField->getOptions()['validator']);
        $this->assertNull($formField->getOptions()['transform']);
        $this->assertNull($formField->getOptions()['reverse']);

        $formField0 = $formField->getField(0);
        $this->assertNull($formField0);

        $formField = $formView->getField('mainCategory');
        $this->assertInstanceOf(FormView::class, $formField);
        $this->assertEquals('invoice[mainCategory]', $formField->getName());
        $this->assertEquals('invoice_mainCategory', $formField->getId());
        $this->assertEquals('radiobuttonType', $formField->getType());
        $this->assertNull($formField->getValue());
        $this->assertEquals('Kategoria główna', $formField->getOptions()['label']);
        $this->assertNull($formField->getOptions()['form']);
        $this->assertEquals('test\forms\helpers\entities\InvoiceCategory', $formField->getOptions()['class']);
        $this->assertNull($formField->getOptions()['entityClass']);
        $this->assertEquals('Kategoria 1', $formField->getOptions()['choices']['222']);
        $this->assertEquals('Kategoria 3', $formField->getOptions()['choices']['444']);
        $this->assertNull($formField->getOptions()['hint']);
        $this->assertNull($formField->getOptions()['validator']);
        $this->assertNull($formField->getOptions()['transform']);
        $this->assertNull($formField->getOptions()['reverse']);
        $this->assertNull($formField->getOptions()['template']);
        $this->assertFalse($formField->getOptions()['nullable']);
        $this->assertNull($formView->getError());

        $formField = $formView->getField('customer');

        $nameField = $formField->getField('name');
        $this->assertInstanceOf(FormView::class, $nameField);
        $this->assertEquals('invoice[customer][name]', $nameField->getName());
        $this->assertEquals('invoice_customer_name', $nameField->getId());
        $this->assertEquals('textType', $nameField->getType());
        $this->assertNull($nameField->getValue());
        $this->assertEquals('Nazwa kontrahenta', $nameField->getOptions()['label']);
        $this->assertNull($formView->getError());

        $birthDateField = $formField->getField('birthDate');
        $this->assertInstanceOf(FormView::class, $birthDateField);
        $this->assertEquals('invoice[customer][birthDate]', $birthDateField->getName());
        $this->assertEquals('invoice_customer_birthDate', $birthDateField->getId());
        $this->assertEquals('dateTimeType', $birthDateField->getType());
        $this->assertNull($birthDateField->getValue());
        $this->assertEquals('Data urodzenia', $birthDateField->getOptions()['label']);
        $this->assertNull($formView->getError());

        $userField = $formField->getField('user');
        $userLoginField = $userField->getField('login');
        $this->assertInstanceOf(FormView::class, $userLoginField);
        $this->assertEquals('invoice[customer][user][login]', $userLoginField->getName());
        $this->assertEquals('invoice_customer_user_login', $userLoginField->getId());
        $this->assertEquals('textType', $userLoginField->getType());
        $this->assertNull($userLoginField->getValue());
        $this->assertEquals('Login', $userLoginField->getOptions()['label']);
        $this->assertNull($userLoginField->getError());

        $userPasswordField = $userField->getField('password');
        $this->assertInstanceOf(FormView::class, $userPasswordField);
        $this->assertEquals('invoice[customer][user][password]', $userPasswordField->getName());
        $this->assertEquals('invoice_customer_user_password', $userPasswordField->getId());
        $this->assertEquals('passwordType', $userPasswordField->getType());
        $this->assertNull($userPasswordField->getValue());
        $this->assertEquals('Hasło', $userPasswordField->getOptions()['label']);
        $this->assertNull($userPasswordField->getError());

        $userLastLoginField = $userField->getField('lastLogin');
        $this->assertInstanceOf(FormView::class, $userLastLoginField);
        $this->assertEquals('invoice[customer][user][lastLogin]', $userLastLoginField->getName());
        $this->assertEquals('invoice_customer_user_lastLogin', $userLastLoginField->getId());
        $this->assertEquals('dateTimeType', $userLastLoginField->getType());
        $this->assertNull($userLastLoginField->getValue());
        $this->assertEquals('Data ostatniego logowania', $userLastLoginField->getOptions()['label']);
        $this->assertNull($userLastLoginField->getError());

        $formField = $formView->getField('warehouseDocuments');
        $this->assertInstanceOf(FormView::class, $formField);
        $this->assertEquals('invoice[warehouseDocuments]', $formField->getName());
        $this->assertEquals('invoice_warehouseDocuments', $formField->getId());
        $this->assertEquals('multiSelectType', $formField->getType());
        $this->assertEmpty($formField->getValue());
        $this->assertEquals('Dokumenty magazynowe', $formField->getOptions()['label']);
        $this->assertEquals('test\forms\helpers\entities\WarehouseDocument', $formField->getOptions()['class']);
        $this->assertEquals('PZ-1/01/2021', $formField->getOptions()['choices'][5]);
        $this->assertEquals('PZ-2/01/2021', $formField->getOptions()['choices'][6]);
        $this->assertEquals('PZ-3/01/2021', $formField->getOptions()['choices'][7]);
        $this->assertEquals('PZ-4/01/2021', $formField->getOptions()['choices'][8]);
        $this->assertNull($formView->getError());

        $formField = $formView->getField('categories');
        $this->assertInstanceOf(FormView::class, $formField);
        $this->assertEquals('invoice[categories]', $formField->getName());
        $this->assertEquals('invoice_categories', $formField->getId());
        $this->assertEquals('checkboxType', $formField->getType());
        $this->assertEmpty($formField->getValue());
        $this->assertEquals('Kategorie faktury', $formField->getOptions()['label']);
        $this->assertEquals('test\forms\helpers\entities\InvoiceCategory', $formField->getOptions()['class']);
        $this->assertEquals('Kategoria 1', $formField->getOptions()['choices'][222]);
        $this->assertEquals('Kategoria 2', $formField->getOptions()['choices'][333]);
        $this->assertEquals('Kategoria 3', $formField->getOptions()['choices'][444]);
        $this->assertNull($formView->getError());

        $formField = $formView->getField('paymentDays');
        $this->assertInstanceOf(FormView::class, $formField);
        $this->assertEquals('invoice[paymentDays]', $formField->getName());
        $this->assertEquals('invoice_paymentDays', $formField->getId());
        $this->assertEquals('integerType', $formField->getType());
        $this->assertNull($formField->getValue());
        $this->assertEquals('Dni na płatność', $formField->getOptions()['label']);
        $this->assertNull($formView->getError());

        $formField = $formView->getField('notices');
        $this->assertInstanceOf(FormView::class, $formField);
        $this->assertEquals('invoice[notices]', $formField->getName());
        $this->assertEquals('invoice_notices', $formField->getId());
        $this->assertEquals('textareaType', $formField->getType());
        $this->assertNull($formField->getValue());
        $this->assertEquals('Notatki', $formField->getOptions()['label']);
        $this->assertNull($formView->getError());

        $formField = $formView->getField('payer');
        $this->assertInstanceOf(FormView::class, $formField);
        $this->assertEquals('invoice[payer]', $formField->getName());
        $this->assertEquals('invoice_payer', $formField->getId());
        $this->assertEquals('selectType', $formField->getType());
        $this->assertNull($formField->getValue());
        $this->assertEquals('Płatnik', $formField->getOptions()['label']);
        $this->assertEquals('test\forms\helpers\entities\Customer', $formField->getOptions()['class']);
        $this->assertEquals('Kontrahent 1', $formField->getOptions()['choices'][4]);
        $this->assertEquals('Kontrahent 2', $formField->getOptions()['choices'][5]);
        $this->assertEquals('Kontrahent 3', $formField->getOptions()['choices'][6]);
        $this->assertNull($formView->getError());

        $formField = $formView->getField('mainCategory');
        $this->assertInstanceOf(FormView::class, $formField);
        $this->assertEquals('invoice[mainCategory]', $formField->getName());
        $this->assertEquals('invoice_mainCategory', $formField->getId());
        $this->assertEquals('radiobuttonType', $formField->getType());
        $this->assertNull($formField->getValue());
        $this->assertEquals('Kategoria główna', $formField->getOptions()['label']);
        $this->assertEquals('test\forms\helpers\entities\InvoiceCategory', $formField->getOptions()['class']);
        $this->assertEquals('Kategoria 1', $formField->getOptions()['choices'][222]);
        $this->assertEquals('Kategoria 3', $formField->getOptions()['choices'][444]);
        $this->assertNull($formView->getError());

        $formField = $formView->getField('files');
        $this->assertInstanceOf(FormView::class, $formField);
        $this->assertEquals('invoice[files]', $formField->getName());
        $this->assertEquals('invoice_files', $formField->getId());
        $this->assertEquals('fileType', $formField->getType());
        $this->assertNull($formField->getValue());
        $this->assertEquals('Pliki', $formField->getOptions()['label']);
        $this->assertIsCallable($formField->getOptions()['transform']);
        $this->assertNull($formView->getError());

        $this->assertNotNull($formView->getFields()['invoiceNumber']);
        $this->assertNotNull($formView->getFields()['dateTime']);
        $this->assertNotNull($formView->getFields()['positions']);
    }

    public function testCreateFormViewWithEntity()
    {
        $invoice = createInvoice();

        $invoiceForm = new InvoiceForm($invoice, [
            'dbBridge' => new TestDbBridge(),
        ]);

        $formView = $invoiceForm->createFormView();

        $this->assertEquals('invoice', $formView->getName());
        $this->assertEquals('invoice', $formView->getId());
        $this->assertEquals('form', $formView->getType());
        $this->assertNull($formView->getValue());
        $this->assertEmpty($formView->getOptions());
        $this->assertEmpty($formView->getPath());
        $this->assertNull($formView->getError());

        $formField = $formView->getField('invoiceNumber');
        $this->assertInstanceOf(FormView::class, $formField);
        $this->assertEquals('invoice[invoiceNumber]', $formField->getName());
        $this->assertEquals('invoice_invoiceNumber', $formField->getId());
        $this->assertEquals('textType', $formField->getType());
        $this->assertEquals('FV-1/01/2021', $formField->getValue());
        $this->assertEquals('Numer faktury', $formField->getOptions()['label']);
        $this->assertNull($formField->getOptions()['form']);
        $this->assertNull($formField->getOptions()['class']);
        $this->assertNull($formField->getOptions()['entityClass']);
        $this->assertEmpty($formField->getOptions()['choices']);
        $this->assertNull($formField->getOptions()['hint']);
        $this->assertNull($formField->getOptions()['validator']);
        $this->assertNull($formField->getOptions()['transform']);
        $this->assertNull($formField->getOptions()['reverse']);
        $this->assertNull($formField->getOptions()['template']);
        $this->assertFalse($formField->getOptions()['nullable']);
        $this->assertNull($formView->getError());

        $formField = $formView->getField('dateTime');
        $this->assertInstanceOf(FormView::class, $formField);
        $this->assertEquals('invoice[dateTime]', $formField->getName());
        $this->assertEquals('invoice_dateTime', $formField->getId());
        $this->assertEquals('dateTimeType', $formField->getType());
        $this->assertEquals('2021-10-15 08:08:08', $formField->getValue());
        $this->assertEquals('Data i czas wystawienia', $formField->getOptions()['label']);
        $this->assertNull($formField->getOptions()['form']);
        $this->assertNull($formField->getOptions()['class']);
        $this->assertNull($formField->getOptions()['entityClass']);
        $this->assertEmpty($formField->getOptions()['choices']);
        $this->assertNull($formField->getOptions()['hint']);
        $this->assertNull($formField->getOptions()['validator']);
        $this->assertNull($formField->getOptions()['transform']);
        $this->assertNull($formField->getOptions()['reverse']);
        $this->assertEquals('exampleDateField.tpl', $formField->getOptions()['template']);
        $this->assertFalse($formField->getOptions()['nullable']);
        $this->assertNull($formView->getError());

        $formField = $formView->getField('positions');

        $this->assertNull($formField->getName());
        $this->assertNull($formField->getId());
        $this->assertEquals('collection', $formField->getType());
        $this->assertNull($formField->getValue());

        $this->assertEquals('test\forms\helpers\forms\InvoicePositionForm', $formField->getOptions()['class']);
        $this->assertEquals('test\forms\helpers\entities\InvoicePosition', $formField->getOptions()['entityClass']);
        $this->assertEquals('Pozycje', $formField->getOptions()['label']);
        $this->assertEquals('positions.tpl', $formField->getOptions()['template']);
        $this->assertTrue($formField->getOptions()['mapped']);
        $this->assertFalse($formField->getOptions()['nullable']);
        $this->assertNull($formField->getOptions()['form']);
        $this->assertNull($formField->getOptions()['choices']);
        $this->assertNull($formField->getOptions()['hint']);
        $this->assertNull($formField->getOptions()['validator']);
        $this->assertNull($formField->getOptions()['transform']);
        $this->assertNull($formField->getOptions()['reverse']);

        $formField0 = $formField->getField(0);
        $this->assertNull($formField0->getName());
        $this->assertNull($formField0->getId());
        $this->assertNull($formField0->getType());
        $this->assertNull($formField0->getValue());

        $fieldName = $formField0->getField('name');
        $this->assertEquals('invoice[positions][0][name]', $fieldName->getName());
        $this->assertEquals('invoice_positions_0_name', $fieldName->getId());
        $this->assertEquals('textType', $fieldName->getType());
        $this->assertEquals('Pozycja 1', $fieldName->getValue());
        $this->assertEquals('Nazwa produktu', $fieldName->getOptions()['label']);

        $fieldName = $formField0->getField('quantity');
        $this->assertEquals('invoice[positions][0][quantity]', $fieldName->getName());
        $this->assertEquals('invoice_positions_0_quantity', $fieldName->getId());
        $this->assertEquals('textType', $fieldName->getType());
        $this->assertEquals('1', $fieldName->getValue());
        $this->assertEquals('Ilość', $fieldName->getOptions()['label']);

        $fieldName = $formField0->getField('sendDate');
        $this->assertEquals('invoice[positions][0][sendDate]', $fieldName->getName());
        $this->assertEquals('invoice_positions_0_sendDate', $fieldName->getId());
        $this->assertEquals('dateTimeType', $fieldName->getType());
        $this->assertEquals('2021-10-15 01:01:01', $fieldName->getValue());
        $this->assertEquals('Data wysyłki', $fieldName->getOptions()['label']);

        $formField1 = $formField->getField(1);
        $this->assertNull($formField1->getName());
        $this->assertNull($formField1->getId());
        $this->assertNull($formField1->getType());
        $this->assertNull($formField1->getValue());

        $fieldName = $formField1->getField('name');
        $this->assertEquals('invoice[positions][1][name]', $fieldName->getName());
        $this->assertEquals('invoice_positions_1_name', $fieldName->getId());
        $this->assertEquals('textType', $fieldName->getType());
        $this->assertEquals('Pozycja 2', $fieldName->getValue());
        $this->assertEquals('Nazwa produktu', $fieldName->getOptions()['label']);

        $fieldName = $formField1->getField('quantity');
        $this->assertEquals('invoice[positions][1][quantity]', $fieldName->getName());
        $this->assertEquals('invoice_positions_1_quantity', $fieldName->getId());
        $this->assertEquals('textType', $fieldName->getType());
        $this->assertEquals('2', $fieldName->getValue());
        $this->assertEquals('Ilość', $fieldName->getOptions()['label']);

        $fieldName = $formField1->getField('sendDate');
        $this->assertEquals('invoice[positions][1][sendDate]', $fieldName->getName());
        $this->assertEquals('invoice_positions_1_sendDate', $fieldName->getId());
        $this->assertEquals('dateTimeType', $fieldName->getType());
        $this->assertNull($fieldName->getValue());
        $this->assertEquals('Data wysyłki', $fieldName->getOptions()['label']);
        $this->assertIsCallable($fieldName->getOptions()['validator']);
        
        $formField = $formView->getField('mainCategory');
        $this->assertInstanceOf(FormView::class, $formField);
        $this->assertEquals('invoice[mainCategory]', $formField->getName());
        $this->assertEquals('invoice_mainCategory', $formField->getId());
        $this->assertEquals('radiobuttonType', $formField->getType());
        $this->assertEquals('333', $formField->getValue());
        $this->assertEquals('Kategoria główna', $formField->getOptions()['label']);
        $this->assertNull($formField->getOptions()['form']);
        $this->assertEquals('test\forms\helpers\entities\InvoiceCategory', $formField->getOptions()['class']);
        $this->assertNull($formField->getOptions()['entityClass']);
        $this->assertEquals('Kategoria 1', $formField->getOptions()['choices']['222']);
        $this->assertEquals('Kategoria 3', $formField->getOptions()['choices']['444']);
        $this->assertNull($formField->getOptions()['hint']);
        $this->assertNull($formField->getOptions()['validator']);
        $this->assertNull($formField->getOptions()['transform']);
        $this->assertNull($formField->getOptions()['reverse']);
        $this->assertNull($formField->getOptions()['template']);
        $this->assertFalse($formField->getOptions()['nullable']);
        $this->assertNull($formView->getError());

        $formField = $formView->getField('customer');

        $nameField = $formField->getField('name');
        $this->assertInstanceOf(FormView::class, $nameField);
        $this->assertEquals('invoice[customer][name]', $nameField->getName());
        $this->assertEquals('invoice_customer_name', $nameField->getId());
        $this->assertEquals('textType', $nameField->getType());
        $this->assertEquals('Kontrahent 1', $nameField->getValue());
        $this->assertEquals('Nazwa kontrahenta', $nameField->getOptions()['label']);
        $this->assertNull($formView->getError());

        $birthDateField = $formField->getField('birthDate');
        $this->assertInstanceOf(FormView::class, $birthDateField);
        $this->assertEquals('invoice[customer][birthDate]', $birthDateField->getName());
        $this->assertEquals('invoice_customer_birthDate', $birthDateField->getId());
        $this->assertEquals('dateTimeType', $birthDateField->getType());
        $this->assertEquals('1953-03-03 00:00:00', $birthDateField->getValue());
        $this->assertEquals('Data urodzenia', $birthDateField->getOptions()['label']);
        $this->assertNull($formView->getError());

        $userField = $formField->getField('user');
        $userLoginField = $userField->getField('login');
        $this->assertInstanceOf(FormView::class, $userLoginField);
        $this->assertEquals('invoice[customer][user][login]', $userLoginField->getName());
        $this->assertEquals('invoice_customer_user_login', $userLoginField->getId());
        $this->assertEquals('textType', $userLoginField->getType());
        $this->assertEquals('logintojest', $userLoginField->getValue());
        $this->assertEquals('Login', $userLoginField->getOptions()['label']);
        $this->assertNull($userLoginField->getError());

        $userPasswordField = $userField->getField('password');
        $this->assertInstanceOf(FormView::class, $userPasswordField);
        $this->assertEquals('invoice[customer][user][password]', $userPasswordField->getName());
        $this->assertEquals('invoice_customer_user_password', $userPasswordField->getId());
        $this->assertEquals('passwordType', $userPasswordField->getType());
        $this->assertEquals('aaaaaaaaaaaaa', $userPasswordField->getValue());
        $this->assertEquals('Hasło', $userPasswordField->getOptions()['label']);
        $this->assertNull($userPasswordField->getError());

        $userLastLoginField = $userField->getField('lastLogin');
        $this->assertInstanceOf(FormView::class, $userLastLoginField);
        $this->assertEquals('invoice[customer][user][lastLogin]', $userLastLoginField->getName());
        $this->assertEquals('invoice_customer_user_lastLogin', $userLastLoginField->getId());
        $this->assertEquals('dateTimeType', $userLastLoginField->getType());
        $this->assertEquals('2021-08-01 02:02:02', $userLastLoginField->getValue());
        $this->assertEquals('Data ostatniego logowania', $userLastLoginField->getOptions()['label']);
        $this->assertNull($userLastLoginField->getError());

        $formField = $formView->getField('warehouseDocuments');
        $this->assertInstanceOf(FormView::class, $formField);
        $this->assertEquals('invoice[warehouseDocuments]', $formField->getName());
        $this->assertEquals('invoice_warehouseDocuments', $formField->getId());
        $this->assertEquals('multiSelectType', $formField->getType());
        $this->assertEquals('5', $formField->getValue()[0]);
        $this->assertEquals('6', $formField->getValue()[1]);
        $this->assertEquals('Dokumenty magazynowe', $formField->getOptions()['label']);
        $this->assertEquals('test\forms\helpers\entities\WarehouseDocument', $formField->getOptions()['class']);
        $this->assertEquals('PZ-1/01/2021', $formField->getOptions()['choices'][5]);
        $this->assertEquals('PZ-2/01/2021', $formField->getOptions()['choices'][6]);
        $this->assertEquals('PZ-3/01/2021', $formField->getOptions()['choices'][7]);
        $this->assertEquals('PZ-4/01/2021', $formField->getOptions()['choices'][8]);
        $this->assertNull($formView->getError());

        $formField = $formView->getField('categories');
        $this->assertInstanceOf(FormView::class, $formField);
        $this->assertEquals('invoice[categories]', $formField->getName());
        $this->assertEquals('invoice_categories', $formField->getId());
        $this->assertEquals('checkboxType', $formField->getType());
        $this->assertEquals('222', $formField->getValue()[0]);
        $this->assertEquals('333', $formField->getValue()[1]);
        $this->assertEquals('Kategorie faktury', $formField->getOptions()['label']);
        $this->assertEquals('test\forms\helpers\entities\InvoiceCategory', $formField->getOptions()['class']);
        $this->assertEquals('Kategoria 1', $formField->getOptions()['choices'][222]);
        $this->assertEquals('Kategoria 2', $formField->getOptions()['choices'][333]);
        $this->assertEquals('Kategoria 3', $formField->getOptions()['choices'][444]);
        $this->assertNull($formView->getError());

        $formField = $formView->getField('paymentDays');
        $this->assertInstanceOf(FormView::class, $formField);
        $this->assertEquals('invoice[paymentDays]', $formField->getName());
        $this->assertEquals('invoice_paymentDays', $formField->getId());
        $this->assertEquals('integerType', $formField->getType());
        $this->assertEquals('14', $formField->getValue());
        $this->assertEquals('Dni na płatność', $formField->getOptions()['label']);
        $this->assertNull($formView->getError());


        $formField = $formView->getField('notices');
        $this->assertInstanceOf(FormView::class, $formField);
        $this->assertEquals('invoice[notices]', $formField->getName());
        $this->assertEquals('invoice_notices', $formField->getId());
        $this->assertEquals('textareaType', $formField->getType());
        $this->assertEquals('Example notices', $formField->getValue());
        $this->assertEquals('Notatki', $formField->getOptions()['label']);
        $this->assertNull($formView->getError());

        $formField = $formView->getField('payer');
        $this->assertInstanceOf(FormView::class, $formField);
        $this->assertEquals('invoice[payer]', $formField->getName());
        $this->assertEquals('invoice_payer', $formField->getId());
        $this->assertEquals('selectType', $formField->getType());
        $this->assertEquals('5', $formField->getValue());
        $this->assertEquals('Płatnik', $formField->getOptions()['label']);
        $this->assertEquals('test\forms\helpers\entities\Customer', $formField->getOptions()['class']);
        $this->assertEquals('Kontrahent 1', $formField->getOptions()['choices'][4]);
        $this->assertEquals('Kontrahent 2', $formField->getOptions()['choices'][5]);
        $this->assertEquals('Kontrahent 3', $formField->getOptions()['choices'][6]);
        $this->assertNull($formView->getError());

        $formField = $formView->getField('mainCategory');
        $this->assertInstanceOf(FormView::class, $formField);
        $this->assertEquals('invoice[mainCategory]', $formField->getName());
        $this->assertEquals('invoice_mainCategory', $formField->getId());
        $this->assertEquals('radiobuttonType', $formField->getType());
        $this->assertEquals('333', $formField->getValue());
        $this->assertEquals('Kategoria główna', $formField->getOptions()['label']);
        $this->assertEquals('test\forms\helpers\entities\InvoiceCategory', $formField->getOptions()['class']);
        $this->assertEquals('Kategoria 1', $formField->getOptions()['choices'][222]);
        $this->assertEquals('Kategoria 3', $formField->getOptions()['choices'][444]);
        $this->assertNull($formView->getError());

        $formField = $formView->getField('files');
        $this->assertInstanceOf(FormView::class, $formField);
        $this->assertEquals('invoice[files]', $formField->getName());
        $this->assertEquals('invoice_files', $formField->getId());
        $this->assertEquals('fileType', $formField->getType());
        $this->assertEquals('1234', $formField->getValue()[0]->getId());
        $this->assertEquals('file.txt', $formField->getValue()[0]->getName());
        $this->assertEquals('text/plain', $formField->getValue()[0]->getType());
        $this->assertEquals('1', $formField->getValue()[0]->getSize());
        $this->assertEquals('Pliki', $formField->getOptions()['label']);
        $this->assertIsCallable($formField->getOptions()['transform']);
        $this->assertNull($formView->getError());
    }

    public function testValidProcessRequestWithoutEntity()
    {
        $examplePostData =
        [
            "invoice" => [
                "invoiceNumber" => "FV-2/02/2022",
                "dateTime" => "2022-02-02 01:01:01",
                "positions" => [
                    0 => [
                        "name" => "Pozycja 1111",
                        "quantity" => "11",
                        "sendDate" => "2021-02-02 02:02:02",
                        "price" => "12,50",
                    ],
                    1 => [
                        "name" => "Pozycja 2222",
                        "quantity" => "22",
                        "sendDate" => "",
                        "price" => "123.33",
                    ]
                ],
                "customer" => [
                    "name" => "Kontrahent 1111",
                    "birthDate" => "1953-03-03 22:22:22",
                    "user" => [
                        "login" => "logintojest",
                        "password" => "testowehaslo",
                        "lastLogin" => "2021-03-03 03:03:03",
                    ]
                ],
                "warehouseDocuments" => ["6", "7", "8"],
                "categories" => ["333", "444"],
                "paymentDays" => "21",
                "notices" => "Example notices test 2",
                "payer" => "4",
                "mainCategory" => "222",
                "mysteryValue" => "mystery value 2"
            ]
        ];

        $_SERVER['REQUEST_METHOD'] = 'POST';

        $invoiceForm = new InvoiceForm();

        $invoiceForm->processRequest($examplePostData);

        $formField = $invoiceForm->getField('invoiceNumber');
        $this->assertEquals('invoiceNumber', $formField->getFieldName());
        $this->assertEquals('FV-2/02/2022', $formField->getFormValue());
        $this->assertEquals('FV-2/02/2022', $formField->getValue());

        $formField = $invoiceForm->getField('dateTime');
        $this->assertEquals('dateTime', $formField->getFieldName());
        $this->assertEquals('2022-02-02 01:01:01', $formField->getFormValue());
        $this->assertInstanceOf(DateTime::class, $formField->getValue());
        $this->assertEquals('2022-02-02 01:01:01', $formField->getValue()->format('Y-m-d H:i:s'));

        $formField0 = $invoiceForm->getField('positions')->getField(0);

        $fieldName = $formField0['name'];
        $this->assertEquals('Pozycja 1111', $fieldName->getValue());
        $this->assertEquals('Pozycja 1111', $fieldName->getFormValue());

        $fieldName = $formField0['quantity'];
        $this->assertEquals('11', $fieldName->getValue());
        $this->assertEquals('11', $fieldName->getFormValue());

        $fieldName = $formField0['sendDate'];
        $this->assertEquals('sendDate', $fieldName->getFieldName());
        $this->assertEquals('2021-02-02 02:02:02', $fieldName->getFormValue());
        $this->assertInstanceOf(DateTime::class, $fieldName->getValue());
        $this->assertEquals('2021-02-02 02:02:02', $fieldName->getValue()->format('Y-m-d H:i:s'));

        $fieldName = $formField0['price'];
        $this->assertEquals(12.5, $fieldName->getValue());
        $this->assertEquals('12,50', $fieldName->getFormValue());

        $formField1 = $invoiceForm->getField('positions')->getField(1);
        $fieldName = $formField1['name'];
        $this->assertEquals('Pozycja 2222', $fieldName->getValue());
        $this->assertEquals('Pozycja 2222', $fieldName->getFormValue());

        $fieldName = $formField1['quantity'];
        $this->assertEquals('22', $fieldName->getValue());
        $this->assertEquals('22', $fieldName->getFormValue());

        $fieldName = $formField1['sendDate'];
        $this->assertEquals('sendDate', $fieldName->getFieldName());
        $this->assertEquals('', $fieldName->getFormValue());
        $this->assertNull($fieldName->getValue());

        $fieldName = $formField1['price'];
        $this->assertEquals('123.33', $fieldName->getValue());
        $this->assertEquals('123.33', $fieldName->getFormValue());

        $formCustomer = $invoiceForm->getField('customer');
        $formField = $formCustomer->getField('name');
        $this->assertEquals('Kontrahent 1111', $formField->getValue());
        $this->assertEquals('Kontrahent 1111', $formField->getFormValue());

        $formField = $formCustomer->getField('birthDate');
        $this->assertEquals('1953-03-03 22:22:22', $formField->getFormValue());
        $this->assertInstanceOf(DateTime::class, $formField->getValue());
        $this->assertEquals('1953-03-03 22:22:22', $formField->getValue()->format('Y-m-d H:i:s'));

        $formUser = $formCustomer->getField('user');
        $formField = $formUser->getField('login');
        $this->assertEquals('logintojest', $formField->getValue());
        $this->assertEquals('logintojest', $formField->getFormValue());

        $formField = $formUser->getField('password');
        $this->assertEquals('testowehaslo', $formField->getValue());
        $this->assertEquals('testowehaslo', $formField->getFormValue());

        $formField = $formUser->getField('lastLogin');
        $this->assertEquals('2021-03-03 03:03:03', $formField->getFormValue());
        $this->assertInstanceOf(DateTime::class, $formField->getValue());
        $this->assertEquals('2021-03-03 03:03:03', $formField->getValue()->format('Y-m-d H:i:s'));

        $formField = $invoiceForm->getField('warehouseDocuments');
        $this->assertIsArray($formField->getValue());
        $this->assertIsArray($formField->getFormValue());

        $this->assertEquals('6', $formField->getValue()[0]);
        $this->assertEquals('7', $formField->getValue()[1]);
        $this->assertEquals('8', $formField->getValue()[2]);

        $this->assertEquals('6', $formField->getFormValue()[0]);
        $this->assertEquals('7', $formField->getFormValue()[1]);
        $this->assertEquals('8', $formField->getFormValue()[2]);

        $formField = $invoiceForm->getField('categories');
        $this->assertIsArray($formField->getValue());
        $this->assertIsArray($formField->getFormValue());
        $this->assertEquals('333', $formField->getValue()[0]);
        $this->assertEquals('444', $formField->getValue()[1]);
        $this->assertEquals('333', $formField->getFormValue()[0]);
        $this->assertEquals('444', $formField->getFormValue()[1]);

        $formField = $invoiceForm->getField('paymentDays');
        $this->assertEquals('21', $formField->getValue());
        $this->assertEquals('21', $formField->getFormValue());

        $formField = $invoiceForm->getField('notices');
        $this->assertEquals('Example notices test 2', $formField->getValue());
        $this->assertEquals('Example notices test 2', $formField->getFormValue());

        $formField = $invoiceForm->getField('payer');
        $this->assertEquals('4', $formField->getValue());
        $this->assertEquals('4', $formField->getFormValue());

        $formField = $invoiceForm->getField('mainCategory');
        $this->assertEquals('222', $formField->getValue());
        $this->assertEquals('222', $formField->getFormValue());

        $formField = $invoiceForm->getField('mysteryValue');
        $this->assertEquals('mystery value 2', $formField->getValue());
        $this->assertEquals('mystery value 2', $formField->getFormValue());
    }

    public function testValidProcessRequestWithEntity1()
    {
        $examplePostData =
            [
                "invoice" => [
                    "invoiceNumber" => "FV-2/02/2022",
                    "dateTime" => "2022-02-02 01:01:01",
                    "positions" => [
                        0 => [
                            "name" => "Pozycja 1111",
                            "quantity" => "11",
                            "sendDate" => "2021-02-02 02:02:02",
                            "price" => "1.23",
                        ],
                        1 => [
                            "name" => "Pozycja 2222",
                            "quantity" => "22",
                            "sendDate" => "",
                            "price" => "2.45",
                        ]
                    ],
                    "customer" => [
                        "name" => "Kontrahent 1111",
                        "birthDate" => "1953-03-03 22:22:22",
                        "user" => [
                            "login" => "logintojest",
                            "password" => "testowehaslo",
                            "lastLogin" => "2021-03-03 03:03:03",
                        ]
                    ],
                    "warehouseDocuments" => ["6", "7", "8"],
                    "categories" => ["333", "444"],
                    "paymentDays" => "21",
                    "notices" => "Example notices test 2",
                    "payer" => "4",
                    "mainCategory" => "222",
                ]
            ];

        $_SERVER['REQUEST_METHOD'] = 'POST';

        $invoice = createInvoice();

        $invoiceForm = new InvoiceForm($invoice, [
            'dbBridge' => new TestDbBridge(),
        ]);
        $invoiceForm->processRequest($examplePostData);


        /** @var Invoice $invoice */
        $invoice = $invoiceForm->getEntity();
        $this->assertEquals('FV-2/02/2022', $invoice->getInvoiceNumber());
        $this->assertInstanceOf(DateTime::class, $invoice->getDateTime());
        $this->assertEquals('2022-02-02 01:01:01', $invoice->getDateTime()->format('Y-m-d H:i:s'));
        $this->assertEquals('Example notices test 2', $invoice->getNotices());
        $this->assertEquals('4', $invoice->getPayer()->getId());
        $this->assertEquals('Kontrahent 1', $invoice->getPayer()->getName());
        $this->assertEquals('222', $invoice->getMainCategory()->getId());
        $this->assertEquals('Kategoria 1', $invoice->getMainCategory()->getName());
        $this->assertEquals('21', $invoice->getPaymentDays());

        $this->assertEquals('Pozycja 1111', $invoice->getPositions()[0]->getName());
        $this->assertEquals('11', $invoice->getPositions()[0]->getQuantity());
        $this->assertInstanceOf(DateTime::class, $invoice->getPositions()[0]->getSendDate());
        $this->assertEquals('2021-02-02 02:02:02', $invoice->getPositions()[0]->getSendDate()->format('Y-m-d H:i:s'));
        $this->assertEquals('1.23', $invoice->getPositions()[0]->getPrice());

        $this->assertEquals('Pozycja 2222', $invoice->getPositions()[1]->getName());
        $this->assertEquals('22', $invoice->getPositions()[1]->getQuantity());
        $this->assertNull($invoice->getPositions()[1]->getSendDate());
        $this->assertEquals('2.45', $invoice->getPositions()[1]->getPrice());

        $this->assertEquals('Kontrahent 1111', $invoice->getCustomer()->getName());
        $this->assertInstanceOf(DateTime::class, $invoice->getCustomer()->getBirthDate());
        $this->assertEquals('1953-03-03 22:22:22', $invoice->getCustomer()->getBirthDate()->format('Y-m-d H:i:s'));
        $this->assertEquals('logintojest', $invoice->getCustomer()->getUser()->getLogin());
        $this->assertEquals('d093f1588c7ef822e4ac0bd635c8ffe7', $invoice->getCustomer()->getUser()->getPassword());
        $this->assertInstanceOf(DateTime::class, $invoice->getCustomer()->getUser()->getLastLogin());
        $this->assertEquals('2021-03-03 03:03:03', $invoice->getCustomer()->getUser()->getLastLogin()->format('Y-m-d H:i:s'));

        $this->assertInstanceOf(WarehouseDocument::class, $invoice->getWarehouseDocuments()[0]);
        $this->assertEquals('6', $invoice->getWarehouseDocuments()[0]->getId());
        $this->assertEquals('PZ-2/01/2021', $invoice->getWarehouseDocuments()[0]->getWarehouseDocumentNumber());
        $this->assertInstanceOf(WarehouseDocument::class, $invoice->getWarehouseDocuments()[1]);
        $this->assertEquals('7', $invoice->getWarehouseDocuments()[1]->getId());
        $this->assertEquals('PZ-3/01/2021', $invoice->getWarehouseDocuments()[1]->getWarehouseDocumentNumber());
        $this->assertInstanceOf(WarehouseDocument::class, $invoice->getWarehouseDocuments()[2]);
        $this->assertEquals('8', $invoice->getWarehouseDocuments()[2]->getId());
        $this->assertEquals('PZ-4/01/2021', $invoice->getWarehouseDocuments()[2]->getWarehouseDocumentNumber());

        $this->assertInstanceOf(InvoiceCategory::class, $invoice->getCategories()[0]);
        $this->assertEquals('333', $invoice->getCategories()[0]->getId());
        $this->assertEquals('Kategoria 2', $invoice->getCategories()[0]->getName());

        $this->assertInstanceOf(InvoiceCategory::class, $invoice->getCategories()[1]);
        $this->assertEquals('444', $invoice->getCategories()[1]->getId());
        $this->assertEquals('Kategoria 3', $invoice->getCategories()[1]->getName());
    }

    public function testValidProcessRequestWithEntity2()
    {
        $examplePostData =
            [
                "invoice" => [
                    "invoiceNumber" => "FV-2/02/2022",
                    "dateTime" => "2022-02-02 01:01:01",
                    "positions" => [
                        0 => [
                            "name" => "Pozycja 1111",
                            "quantity" => "11",
                            "sendDate" => "2021-02-02 02:02:02",
                            "price" => "1,23",
                        ],
                        3 => [
                            "name" => "Pozycja 333",
                            "quantity" => "33",
                            "sendDate" => "2021-08-08 10:10:10",
                            "price" => "12,50",
                        ],
                        4 => [
                            "name" => "Pozycja 444",
                            "quantity" => "44",
                            "sendDate" => "2021-07-07 10:10:10",
                            "price" => "13,00",
                        ],
                        5 => [
                            "name" => "Pozycja 555",
                            "quantity" => "55",
                            "sendDate" => "",
                            "price" => "14,00",
                        ]
                    ],
                    "customer" => [
                        "name" => "Kontrahent 1111",
                        "birthDate" => "1953-03-03 22:22:22",
                        "user" => [
                            "login" => "logintojest",
                            "password" => "testowehaslo",
                            "lastLogin" => "2021-03-03 03:03:03",
                        ]
                    ],
                    "warehouseDocuments" => ["6", "7", "8"],
                    "categories" => ["333", "444"],
                    "paymentDays" => "21",
                    "notices" => "Example notices test 2",
                    "payer" => "4",
                    "mainCategory" => "222",
                ]
            ];

        $_SERVER['REQUEST_METHOD'] = 'POST';

        $invoice = createInvoice();

        $invoiceForm = new InvoiceForm($invoice, [
            'dbBridge' => new TestDbBridge(),
        ]);
        $valid = $invoiceForm->processRequest($examplePostData);

        $this->assertTrue($valid);

        /** @var Invoice $invoice */
        $invoice = $invoiceForm->getEntity();

        $this->assertEquals('Pozycja 1111', $invoice->getPositions()[0]->getName());
        $this->assertEquals('11', $invoice->getPositions()[0]->getQuantity());
        $this->assertInstanceOf(DateTime::class, $invoice->getPositions()[0]->getSendDate());
        $this->assertEquals('2021-02-02 02:02:02', $invoice->getPositions()[0]->getSendDate()->format('Y-m-d H:i:s'));
        $this->assertEquals('1.23', $invoice->getPositions()[0]->getPrice());

        $this->assertFalse(isset($invoice->getPositions()[1]));
        $this->assertFalse(isset($invoice->getPositions()[2]));

        $this->assertEquals('Pozycja 333', $invoice->getPositions()[3]->getName());
        $this->assertEquals('33', $invoice->getPositions()[3]->getQuantity());
        $this->assertInstanceOf(DateTime::class, $invoice->getPositions()[3]->getSendDate());
        $this->assertEquals('2021-08-08 10:10:10', $invoice->getPositions()[3]->getSendDate()->format('Y-m-d H:i:s'));
        $this->assertEquals(12.5, $invoice->getPositions()[3]->getPrice());

        $this->assertEquals('Pozycja 444', $invoice->getPositions()[4]->getName());
        $this->assertEquals('44', $invoice->getPositions()[4]->getQuantity());
        $this->assertInstanceOf(DateTime::class, $invoice->getPositions()[4]->getSendDate());
        $this->assertEquals('2021-07-07 10:10:10', $invoice->getPositions()[4]->getSendDate()->format('Y-m-d H:i:s'));
        $this->assertEquals(13, $invoice->getPositions()[4]->getPrice());

        $this->assertEquals('Pozycja 555', $invoice->getPositions()[5]->getName());
        $this->assertEquals('55', $invoice->getPositions()[5]->getQuantity());
        $this->assertNull($invoice->getPositions()[5]->getSendDate());
        $this->assertEquals(14, $invoice->getPositions()[5]->getPrice());
    }

    public function testNotValidProcessRequestWithEntity1()
    {
        $examplePostData =
            [
                "invoice" => [
                    "invoiceNumber" => "FV-2/02/2022",
                    "dateTime" => "bad_date",
                    "positions" => [
                        0 => [
                            "name" => "",
                            "quantity" => "",
                            "sendDate" => date('Y-m-d H:i:s'),
                            "price" => "asd",
                        ],
                        1 => [
                            "name" => "Test 2",
                            "quantity" => "",
                            "sendDate" => "2021-10-15 01:01:01",
                            "price" => "",
                        ],
                    ],
                    "customer" => [
                        "name" => "Kontrahent 1111",
                        "birthDate" => "",
                        "user" => [
                            "login" => "",
                            "password" => "",
                            "lastLogin" => "",
                        ]
                    ],
                    "warehouseDocuments" => [],
                    "categories" => [],
                    "paymentDays" => "asd",
                    "notices" => "Example notices test 2",
                    "payer" => "4",
                    "mainCategory" => "222",
                ]
            ];

        $_SERVER['REQUEST_METHOD'] = 'POST';

        $invoice = createInvoice();

        $invoiceForm = new InvoiceForm($invoice, [
            'dbBridge' => new TestDbBridge(),
        ]);
        $valid = $invoiceForm->processRequest($examplePostData);

        $this->assertFalse($valid);

        /** @var Invoice $invoice */
        $invoice = $invoiceForm->getEntity();

        $this->assertFalse(isset($invoice->getPositions()[2]));
        $this->assertFalse(isset($invoice->getPositions()[3]));

        $this->assertNotNull($invoiceForm->getErrorForField('invoice_positions_0_name'));
        $this->assertNotNull($invoiceForm->getErrorForField('invoice_positions_0_quantity'));
        $this->assertNotNull($invoiceForm->getErrorForField('invoice_positions_0_sendDate'));
        $this->assertNotNull($invoiceForm->getErrorForField('invoice_positions_0_price'));
        $this->assertNotNull($invoiceForm->getErrorForField('invoice_positions_1_price'));
        $this->assertNotNull($invoiceForm->getErrorForField('invoice_customer_birthDate'));
        $this->assertNotNull($invoiceForm->getErrorForField('invoice_customer_user_login'));
        $this->assertNotNull($invoiceForm->getErrorForField('invoice_customer_user_lastLogin'));

        /** @var FormError $errorForField */
        $errorForField = $invoiceForm->getErrorForField('invoice_positions_0_name');
        $this->assertEquals('Value cannot be empty.', $errorForField->getErrorMessage());
        $this->assertEquals('', $errorForField->getCurrentValue());

        /** @var FormError $errorForField */
        $errorForField = $invoiceForm->getErrorForField('invoice_dateTime');
        $this->assertEquals('Value is not valid.', $errorForField->getErrorMessage());
        $this->assertEquals('bad_date', $errorForField->getCurrentValue());

        $examplePostData['invoice']['paymentDays'] = '';

        $invoiceForm = new InvoiceForm($invoice, [
            'dbBridge' => new TestDbBridge(),
        ]);
        $invoiceForm->processRequest($examplePostData);
        $this->assertNotNull($invoiceForm->getErrorForField('invoice_paymentDays'));

        $examplePostData['invoice']['payer'] = '';

        $invoiceForm = new InvoiceForm($invoice, [
            'dbBridge' => new TestDbBridge(),
        ]);
        $invoiceForm->processRequest($examplePostData);
        $this->assertNotNull($invoiceForm->getErrorForField('invoice_payer'));

        $examplePostData['invoice']['notices'] = '';

        $invoiceForm = new InvoiceForm($invoice, [
            'dbBridge' => new TestDbBridge(),
        ]);
        $invoiceForm->processRequest($examplePostData);
        $this->assertNotNull($invoiceForm->getErrorForField('invoice_notices'));
    }

    public function testNotValidProcessRequestWithEntity2()
    {
        $this->expectException(Exception::class);

        $examplePostData =
            [
                "invoice" => [
                    "nonExistentField" => "test",
                    "invoiceNumber" => "FV-2/02/2022",
                    "dateTime" => "bad_date",
                    "positions" => [
                        0 => [
                            "name" => "",
                            "quantity" => "",
                            "sendDate" => "2021-10-15 01:01:01",
                            "price" => "asd",
                        ],
                        1 => [
                            "name" => "Test 2",
                            "quantity" => "",
                            "sendDate" => "2021-10-15 01:01:01",
                            "price" => "",
                        ],
                    ],
                    "customer" => [
                        "name" => "Kontrahent 1111",
                        "birthDate" => "",
                        "user" => [
                            "login" => "",
                            "password" => "",
                            "lastLogin" => "",
                        ]
                    ],
                    "warehouseDocuments" => [],
                    "categories" => [],
                    "paymentDays" => "asd",
                    "notices" => "Example notices test 2",
                    "payer" => "4",
                    "mainCategory" => "222",
                ]
            ];

        $_SERVER['REQUEST_METHOD'] = 'POST';

        $invoice = createInvoice();

        $invoiceForm = new InvoiceForm($invoice, [
            'dbBridge' => new TestDbBridge(),
        ]);

        $invoiceForm
            ->addField(
                'nonExistentField',
                TextType::class,
                [
                    'label' => 'Nazwa produktu',
                ]
            );

        $invoiceForm->processRequest($examplePostData);
    }

    public function testNotValidProcessRequestWithEntity3()
    {
        $examplePostData =
            [
                "invoice" => [
                    "nonExistentField" => "test",
                    "invoiceNumber" => "FV-2/02/2022",
                    "dateTime" => "bad_date",
                    "positions" => [
                        0 => [
                            "name" => "",
                            "quantity" => "",
                            "sendDate" => "2021-10-15 01:01:01",
                            "price" => "asd",
                        ],
                        1 => [
                            "name" => "Test 2",
                            "quantity" => "",
                            "sendDate" => "2021-10-15 01:01:01",
                            "price" => "",
                        ],
                    ],
                    "customer" => [
                        "name" => "Kontrahent 1111",
                        "birthDate" => "",
                        "user" => [
                            "login" => "",
                            "password" => "",
                            "lastLogin" => "",
                        ]
                    ],
                    "warehouseDocuments" => [],
                    "categories" => [],
                    "paymentDays" => "asd",
                    "notices" => "Example notices test 2",
                    "payer" => "4",
                    "mainCategory" => "222",
                ]
            ];

        $_SERVER['REQUEST_METHOD'] = 'POST';

        $invoice = createInvoice();

        $invoiceForm = new InvoiceForm($invoice, [
            'dbBridge' => new TestDbBridge(),
        ]);

        $invoiceForm
            ->addField(
                'files',
                FileType::class,
                [
                    'label' => 'Pliki',
                    'nullable' => false,
                ]
            );

        $invoiceForm->processRequest($examplePostData);

        $this->assertNotNull($invoiceForm->getErrorForField('invoice_files'));
    }
}
