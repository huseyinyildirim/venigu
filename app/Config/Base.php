<?php

//region Namespace
namespace App\Config\Base;
//endregion

//region Using
use Carbon\Carbon;
//endregion

class Pagination
{
    //region Grid
    public const GRID_LIST_COUNT = 15;
    public const HOMEPAGE_PRODUCT_LIST_COUNT = 25;
    //endregion

    //region Upload
    //region Upload
    public const UPLOAD_MAX_FILE_SIZE            = '4M';
    public const UPLOAD_FILE_TYPES_FOR_IMAGES    = [
        'jpeg',
        'jpg',
        'png',
        'gif',
        'svg'
    ];
    public const UPLOAD_FILE_TYPES_FOR_DOCUMENTS = [
        'pdf',
        'rtf',
        'doc',
        'docx',
        'xls',
        'xlsx',
        'ppt',
        'pptx'
    ];
    //endregion
}

class ThirdParty
{
    public const TinifyApiKey = "bkhkEh4Bjsk5kPKP2O9j5EO65wpo436j";
    public const TinifyCompressionsThisMonth = 500;
}

class UploadFolders
{
    public const PRODUCT = Folders::FILES . DIRECTORY_SEPARATOR . 'product' . DIRECTORY_SEPARATOR;
}

abstract class Folders
{
    public const FILES = DIRECTORY_SEPARATOR . 'files';
}

abstract class Paths
{
    public static function getRoot(): ?string
    {
        $documentRoot = null;

        try {
            if (!empty($_SERVER) && array_key_exists('DOCUMENT_ROOT', $_SERVER)) {
                $documentRoot = $_SERVER['DOCUMENT_ROOT'];
            }
        } catch (\Exception $e) {
            //Functions\Error::instance()->error($e);
        }

        return $documentRoot;
    }

    public static function getFolderFullPath($path): ?string
    {
        $returnString = null;

        try {
            if (!empty($path)) {
                $returnString = $path;

                //region Set Document Root
                $documentRoot = self::getRoot();

                if (null !== $documentRoot) {
                    $returnString = $documentRoot . $returnString;
                }
                //endregion
            }
        }
        catch (\Exception $e) {
            /*Functions\Error::instance()->error($e);*/
        }

        return $returnString;
    }

    public static function getFileWithFullPath($path, $file): ?string
    {
        $returnString = null;

        try {
            if (!empty($path) && !empty($file)) {
                $directorySeparator = $path[\strlen($path) - 1];
                $returnString       = $path . (($directorySeparator === DIRECTORY_SEPARATOR) ? '' : DIRECTORY_SEPARATOR) . $file;

                //region Set Document Root
                $documentRoot = self::getRoot();

                if (null !== $documentRoot) {
                    $returnString = $documentRoot . $returnString;
                }
                //endregion
            }
        }
        catch (\Exception $e) {
            /*Functions\Error::instance()->error($e);*/
        }

        return $returnString;
    }

    public static function getDirectoryWithDate(): ?string
    {
        $returnString = null;

        try {
            $returnString = Carbon::now()->year . DIRECTORY_SEPARATOR . Carbon::now()->month . DIRECTORY_SEPARATOR . Carbon::now()->day . DIRECTORY_SEPARATOR;
        } catch (\Exception $e) {
            //Functions\Error::instance()->error($e);
        }

        return $returnString;
    }

    public static function getFileWithTime()
    {
        $returnString = null;

        try {
            $returnString = Carbon::now()->hour . Carbon::now()->minute . Carbon::now()->second;
        } catch (\Exception $e) {
            //Functions\Error::instance()->error($e);
        }

        return $returnString;
    }

    public static function getRootClearWithFullPath($path)
    {
        $returnString = null;

        try {
            if (!empty($path)) {
                $returnString =str_replace( self::getRoot(),"",$path);
            }
        }
        catch (\Exception $e) {
            /*Functions\Error::instance()->error($e);*/
        }

        return $returnString;
    }
}