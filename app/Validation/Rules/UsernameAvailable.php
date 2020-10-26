<?php

//region Namespace
namespace App\Validation\Rules;
//endregion

//region Using
use App\Models\TblMember;
use Respect\Validation\Rules\AbstractRule;
//endregion

class UsernameAvailable extends AbstractRule
{
    public function validate($input)
    {
        return TblMember::where('username', $input)->count() === 0;
    }
}