<?php

//region Namespace
namespace App\Validation\Exceptions;
//endregion

//region Using
use Respect\Validation\Exceptions\ValidationException;
//endregion

class EmailAvailableException extends ValidationException
{
    public static $defaultTemplates = [
        self::MODE_DEFAULT => [
            self::STANDARD => 'Bu e-posta adresi zaten kayıtlıdır.'
        ]
    ];
}