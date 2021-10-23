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

class FloatType implements SimpleFieldTypeInterface {

    private function unformatFloat($data) : mixed
    {
        $value = str_replace(',', '.', $data);
        return str_replace(' ', '', $value);
    }

    //metoda zamieniająca wartość z formularza na wartość dla encji
    public function transform($data) : float
    {
        return $this->unformatFloat($data);
    }

    //metoda zamieniająca wartość z encji na wartość do formularza
    public function reverse($data) : ?string
    {
        return number_format($data, 2, '.'. '');
    }

    public static function getAlias(): string
    {
        return 'floatType';
    }

    public function validate($data, bool $nullable) : ?FormError
    {
        $formError = null;

        if (!$nullable && (trim($data) == '')) {
            $formError = new FormError();
            $formError->setCurrentValue($data);
            $formError->setErrorMessage('Value cannot be empty.');
        }  elseif (trim($data) != '') {
            $value = $this->unformatFloat($data);

            $filterVar = filter_var($value, FILTER_VALIDATE_FLOAT);
            if ($filterVar === false || $filterVar === null) {
                $formError = new FormError();
                $formError->setCurrentValue($data);
                $formError->setErrorMessage('Value is not valid.');
            }
        }

        return $formError;
    }
}
