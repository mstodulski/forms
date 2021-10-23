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

interface PasswordFieldTypeInterface {

    public function transform($data);
    public function reverse($data) : mixed;
    public static function getAlias() : string;
    public function validate($data, bool $nullable) : ?FormError;
}