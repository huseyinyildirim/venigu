<?php

//region Namespace
namespace App\Functions;
//endregion

//region Using

//endregion

class Network
{
    public static function getIp()
    {
        $returnString = null;

        try {

            if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
                $returnString = $_SERVER['HTTP_CLIENT_IP'];
            } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
                $returnString = $_SERVER['HTTP_X_FORWARDED_FOR'];
            } else {
                $returnString = $_SERVER['REMOTE_ADDR'];
            }
        } catch (\Exception $e) {
            //region Log
            $container->logger->addCritical($e->getMessage());
            //endregion
        }

        return $returnString;
    }
}


