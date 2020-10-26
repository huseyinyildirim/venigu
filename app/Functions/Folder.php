<?php

//region Namespace
namespace App\Functions;
//endregion

//region Using
use App\Config;
//endregion

class Folder
{
    public static function existFile($path): bool
    {
        $returnBool = false;

        try {
            if (file_exists($path) && is_file($path) && is_readable($path)) {
                $returnBool = true;
            }
        }
        catch (\Exception $e) {
            //region Log
            $this->logger->addCritical($e->getMessage());
            //endregion
        }

        return $returnBool;
    }

    public static function unlink($path): bool
    {
        $returnBool = false;

        try {
            if (self::existFile($path)) {
                unlink($path);
                $returnBool = true;
            }
        }
        catch (\Exception $e) {
            //region Log
            $this->logger->addCritical($e->getMessage());
            //endregion
        }

        return $returnBool;
    }

    public static function mkdir($path): bool
    {
        $returnBool = false;

        try {
            if (!is_dir($path)) {
                mkdir($path, 0755, true);
            }
        }
        catch (\Exception $e) {
            //region Log
            $this->logger->addCritical($e->getMessage());
            //endregion
        }

        return $returnBool;
    }

    public static function getFileExtension($file)
    {
        $returnString = null;

        try {
            $returnString = '.' . pathinfo($file, PATHINFO_EXTENSION);
        }
        catch (\Exception $e) {
            //region Log
            $this->logger->addCritical($e->getMessage());
            //endregion
        }

        return $returnString;
    }

    public static function getSefLink($text)
    {
        $returnString = null;

        try {
            $find = array("/Ğ/","/Ü/","/Ş/","/İ/","/Ö/","/Ç/","/ğ/","/ü/","/ş/","/ı/","/ö/","/ç/");
            $changeLetter = array("G","U","S","I","O","C","g","u","s","i","o","c");
            $text = preg_replace("/[^0-9a-zA-ZÄzÜŞİÖÇğüşıöç]/"," ",$text);
            $text = preg_replace($find,$changeLetter,$text);
            $text = preg_replace("/ +/"," ",$text);
            $text = preg_replace("/ /","-",$text);
            $text = preg_replace("/\s/","",$text);
            $text = strtolower($text);
            $text = preg_replace("/^-/","",$text);
            $text = preg_replace("/-$/","",$text);

            $returnString = $text;
        }
        catch (\Exception $e) {
            //region Log
            $this->logger->addCritical($e->getMessage());
            //endregion
        }

        return $returnString;
    }
}