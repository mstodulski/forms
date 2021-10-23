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

class PasswordType implements PasswordFieldTypeInterface {

    public function transform($data)
    {
        return $data;
    }

    public function reverse($data) : ?string
    {
        return $data;
    }

    public static function getAlias(): string
    {
        return 'passwordType';
    }

    public function validate($data, bool $nullable) : ?FormError
    {
        return null;
    }
}
