<?php

//region Namespace
namespace App\Functions;
//endregion

//region Using
use App\Models\TblMember;
//endregion

class Member
{
    public function user()
    {
        //TODO: Where ile arasın
        if (isset($_SESSION['member'])) {
            return TblMember::find($_SESSION['member']);
        }
    }

    public function check()
    {
        return isset($_SESSION['member']);
    }

    public function attempt($mail, $password)
    {
        //TODO: First kullanma null gelebilir
        $member = TblMember::where('mail', $mail)->first();

        if (!user) {
            return false;
        }

        if (password_verify($password, $member->password)) {
            $_SESSION['member'] = $member->id;
            return true;
        }

        return false;
    }

    public function logout()
    {
        unset($_SESSION['member']);
    }
}