<?php

use app\admin\entities\Customer;
use app\admin\entities\Invoice;
use app\admin\entities\InvoiceCategory;
use app\admin\entities\InvoiceFile;
use app\admin\entities\InvoicePosition;
use app\admin\entities\User;
use app\admin\entities\WarehouseDocument;

require_once 'vendor/autoload.php';

function createInvoice()
{
    $invoicePosition1 = new InvoicePosition();
    $invoicePosition1->setId(2);
    $invoicePosition1->setName('Pozycja 1');
    $invoicePosition1->setQuantity(1);
    $invoicePosition1->setSendDate(DateTime::createFromFormat('Y-m-d H:i:s', '2021-10-15 01:01:01'));
    $invoicePosition1->setPrice(1.23);

    $invoicePosition2 = new InvoicePosition();
    $invoicePosition2->setId(3);
    $invoicePosition2->setName('Pozycja 2');
    $invoicePosition2->setQuantity(2);
    $invoicePosition2->setPrice(123.12);

    $invoiceCategory1 = new InvoiceCategory();
    $invoiceCategory1->setId(222);
    $invoiceCategory1->setName('Category 1');

    $invoiceCategory2 = new InvoiceCategory();
    $invoiceCategory2->setId(333);
    $invoiceCategory2->setName('Category 2');

    $invoiceCategory3 = new InvoiceCategory();
    $invoiceCategory3->setId(444);
    $invoiceCategory3->setName('Category 3');

    $user = new User();
    $user->setId(11);
    $user->setLogin('logintojest');
    $user->setPassword('aaaaaaaaaaaaa');
    $user->setLastLogin(DateTime::createFromFormat('Y-m-d H:i:s', '2021-08-01 02:02:02'));

    $customer = new Customer();
    $customer->setId(4);
    $customer->setName('Kontrahent 1');
    $customer->setBirthDate(DateTime::createFromFormat('Y-m-d H:i:s', '1953-03-03 00:00:00'));
    $customer->setUser($user);

    $customer2 = new Customer();
    $customer2->setId(5);
    $customer2->setName('Kontrahent21');

    $warehouseDocument1 = new WarehouseDocument();
    $warehouseDocument1->setId(5);
    $warehouseDocument1->setWarehouseDocumentNumber('PZ-1/01/2021');

    $warehouseDocument2 = new WarehouseDocument();
    $warehouseDocument2->setId(6);
    $warehouseDocument2->setWarehouseDocumentNumber('PZ-2/01/2021');

    $file = new InvoiceFile();
    $file->setSize(1);
    $file->setType('text/plain');
    $file->setName('file.txt');
    $file->setId(1234);

    $files[] = $file;

    $invoice = new Invoice();
    $invoice->setId(7);
    $invoice->setDateTime(DateTime::createFromFormat('Y-m-d H:i:s', '2021-10-15 08:08:08'));
    $invoice->setInvoiceNumber('FV-1/01/2021');
    $invoice->setPositions([$invoicePosition1, $invoicePosition2]);
    $invoice->setWarehouseDocuments([$warehouseDocument1, $warehouseDocument2]);
    $invoice->setCategories([$invoiceCategory1, $invoiceCategory2]);
    $invoice->setCustomer($customer);
    $invoice->setPaymentDays(14);
    $invoice->setNotices('Example notices');
    $invoice->setPayer($customer2);
    $invoice->setMainCategory($invoiceCategory2);
    $invoice->setFiles($files);
    $invoice->setMysteryValue('mystery value');

    return $invoice;
}
