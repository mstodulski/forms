<?php
use test\forms\helpers\forms\InvoiceForm;
use mstodulski\forms\TestDbBridge;

require_once 'tests/bootstrap.php';

try {
    $invoice = createInvoice();
    $smarty = new Smarty();

    $smarty->setTemplateDir('smarty/templates/');
    $smarty->setCompileDir('smarty/templates_c/');
    $smarty->setConfigDir('smarty/configs/');
    $smarty->setCacheDir('smarty/cache/');
    $smarty->debugging = false;

    $invoiceForm = new InvoiceForm($invoice, [
        'dbBridge' => new TestDbBridge(),
    ]);

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {

        if (!empty($_POST)) {
            $valid = $invoiceForm->processRequest($_POST);
            if ($valid) {
                header("HTTP/1.1 303 See Other");
                header('Location: ' . $_SERVER['REQUEST_URI']);
            }
        }
    }

    $formView = $invoiceForm->createFormView();

    $smarty->assign('form', $formView);
    $smarty->display('content.tpl');

} catch (Throwable $e) {
    dump($e);
    die;
}
