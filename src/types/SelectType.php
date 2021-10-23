<?php
/**
 * This file is part of the EasyCore package.
 *
 * (c) Marcin Stodulski <marcin.stodulski@devsprint.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace mstodulski\forms\types;

use mstodulski\forms\FormError;

class SelectType implements SimpleFieldTypeInterface {

    //metoda zamieniająca wartość z formularza na wartość dla encji
    public function transform($data)
    {
        return $data;
    }

    //metoda zamieniająca wartość z encji na wartość z formularza
    public function reverse($data) : string
    {
        return $data;
    }

    public static function getAlias(): string
    {
        return 'selectType';
    }

    public function validate($data, bool $nullable) : ?FormError
    {
        $formError = null;

        if (!$nullable && (trim($data) == '')) {
            $formError = new FormError();
            $formError->setCurrentValue($data);
            $formError->setErrorMessage('Value cannot be empty.');
        }

        return $formError;
    }
}
