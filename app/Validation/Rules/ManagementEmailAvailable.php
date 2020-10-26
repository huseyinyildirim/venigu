<?php

//region Namespace
namespace App\Validation\Rules;
//endregion

//region Using
use App\Models\TblAdmin;
use Respect\Validation\Rules\AbstractRule;
//endregion

class ManagementEmailAvailable extends AbstractRule
{
    public function validate($input)
    {
        return TblAdmin::where('mail', $input)->count() === 0;
    }
}