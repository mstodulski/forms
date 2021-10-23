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

class RadioButtonType implements SimpleFieldTypeInterface {

    //metoda zamieniająca wartość z formularza na wartość dla encji
    public function transform($data)
    {
        return $data;
    }

    //metoda zamieniająca wartość z encji na wartość z formularza
    public function reverse($data) : ?string
    {
        return $data;
    }

    public static function getAlias(): string
    {
        return 'radiobuttonType';
    }

    public function validate($data, bool $nullable) : ?FormError
    {
        return null;
    }
}
