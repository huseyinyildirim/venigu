<?php

//region Namespace
namespace App\Functions;
//endregion

//region Using
use App\Config;
//endregion

class ThirdParty
{
    public static function getTinify($source, $destination)
    {
        $returnBool = false;

        try {

            \Tinify\setKey(Config\Base\ThirdParty::TinifyApiKey);

            $source = \Tinify\fromFile($source);
            $source->toFile($destination);
            $returnBool=true;

        } catch(\Tinify\AccountException $e) {
            print("The error message is: " . $e->getMessage());
            // Verify your API key and account limit.
        } catch(\Tinify\ClientException $e) {
            // Check your source image and request options.
        } catch(\Tinify\ServerException $e) {
            // Temporary issue with the Tinify API.
        } catch(\Tinify\ConnectionException $e) {
            // A network connection error occurred.
        } catch(Exception $e) {
            // Something else went wrong, unrelated to the Tinify API.
        }

        return $returnBool;
    }
}


