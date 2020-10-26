<?php

//region Namespace
namespace App\Functions;
//endregion

//region Using
use App\Models\TblAdmin;
//endregion

class Management
{
    public function admin()
    {
        //TODO: Where ile arasın
        if (isset($_SESSION['management'])) {
            return TblAdmin::find($_SESSION['management']);
        }
    }

    public function check()
    {
        return isset($_SESSION['management']);
    }

    public function attempt($mail, $password)
    {
        $admin = TblAdmin::where('mail', $mail)->first();

        if (!admin) {
            return false;
        }

        if (password_verify($password, $admin->password)) {
            $_SESSION['management'] = $admin->id;
            return true;
        }

        return false;
    }

    public function logout()
    {
        unset($_SESSION['management']);
    }
}