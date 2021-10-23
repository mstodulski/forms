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

class CheckboxType implements CollectionFieldTypeInterface {

    public function transform($data) : mixed
    {
        return $data;
    }

    public function reverse($data) : mixed
    {
        return $data;
    }

    public static function getAlias(): string
    {
        return 'checkboxType';
    }

    public function validate() : ?FormError
    {
        return null;
    }
}
