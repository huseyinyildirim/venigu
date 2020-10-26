<?php

//region Namespace
namespace App\Validation\Rules;
//endregion

//region Using
use App\Models\TblMember;
use Respect\Validation\Rules\AbstractRule;
//endregion

class EmailAvailable extends AbstractRule
{
    public function validate($input)
    {
        return TblMember::where('mail', $input)->count() === 0;
    }
}