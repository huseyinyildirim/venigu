<?php

//region Namespace
namespace App\Validation\Rules;
//endregion

//region Using
use App\Models\TblMember;
use Respect\Validation\Rules\AbstractRule;
//endregion

class PhoneAvailable extends AbstractRule
{
    public function validate($input)
    {
        return TblMember::where('phone', $input)->count() === 0;
    }
}