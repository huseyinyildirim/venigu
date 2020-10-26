<?php

//region Namespace
namespace App\Functions;
//endregion

//region Using

//endregion

class General
{
    public static function clearFormInput($value)
    {
        $returnString = null;

        try {
            if (!empty($value)&&!is_null($value)) {
                $returnString = htmlspecialchars(stripslashes(trim((string)$value)));
            }
        }
        catch (\Exception $e) {
            //region Log
            $this->container->logger->addCritical($e->getMessage());
            //endregion
        }

        return $returnString;
    }

    public static function htmlSpecialCharsDecode($value)
    {
        $returnString = null;

        try {
            if (!empty($value)&&!is_null($value)) {
                $returnString = htmlspecialchars_decode((string)$value);
            }
        }
        catch (\Exception $e) {
            //region Log
            $this->container->logger->addCritical($e->getMessage());
            //endregion
        }

        return $returnString;
    }
}


