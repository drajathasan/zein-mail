<?php
/**
 * @author Drajat Hasan
 * @email drajathasan20@gmail.com
 * @create date 2022-02-24 11:50:16
 * @modify date 2022-02-24 12:27:40
 * @license GPLv3
 * @desc [description]
 */

namespace Zein\Mail;

trait Error
{
    public function getError(string $ExceptionMethod = 'getMessage')
    {
        if (is_object($this->Error) && property_exists($this->Error, $ExceptionMethod)) return $this->Error->{$ExceptionMethod}();
    }
}