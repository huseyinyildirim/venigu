<?php

//region Namespace
namespace App\Functions;
//endregion

//region Using

//endregion

class TryParse
{
    public static function strToBool($value)
    {
        $returnString = false;

        try {
            if (!empty($value)&&!is_null($value)) {
                if ("on" === $value) {
                    $returnString = true;
                }
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


