<?php

//region Namespace
namespace App\Validation\Exceptions;
//endregion

//region Using
use Respect\Validation\Exceptions\ValidationException;
//endregion

class PhoneAvailableException extends ValidationException
{
    public static $defaultTemplates = [
        self::MODE_DEFAULT => [
            self::STANDARD => 'Bu telefon zaten kayıtlıdır.'
        ]
    ];
}