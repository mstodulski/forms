<?php
namespace app\admin\services;

use app\admin\entities\InvoiceFile;
use mstodulski\forms\FormError;

class FileService {

    public static function uploadFilesAndCreateFilesArray(?object $entity = null, ?array $data = null)
    {
        $files = (isset($entity)) ? $entity->getFiles() : [];

        if ($data !== null) {
            foreach ($data as $fileRow) {
                if (move_uploaded_file($fileRow['tmp_name'], $fileRow['name'])) {
                    $file = new InvoiceFile();
                    $file->setName($fileRow['name']);
                    $file->setType($fileRow['type']);
                    $file->setSize($fileRow['size']);

                    $files[] = $file;
                } else {
                    $formError = new FormError();
                    $formError->setErrorMessage('Nie udało się przetworzyć pliku ' . $fileRow['name']);

                    return $formError;
                }
            }
        }

        return $files;
    }

}
