<?php

//region Namespace
namespace App\Validation\Exceptions;
//endregion

//region Using
use Respect\Validation\Exceptions\ValidationException;
//endregion

class MatchesPasswordException extends ValidationException
{
    public static $defaultTemplates = [
        self::MODE_DEFAULT => [
            self::STANDARD => 'Şifreniz uyuşmuyor.'
        ]
    ];
}