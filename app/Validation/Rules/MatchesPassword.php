<?php

//region Namespace
namespace App\Validation\Rules;
//endregion

//region Using
use Respect\Validation\Rules\AbstractRule;
//endregion

class MatchesPassword extends AbstractRule
{
    protected $password;

    public function __construct($password)
    {
        $this->password = $password;
    }

    public function validate($input)
    {
        return password_verify($input,$this->password);
    }
}