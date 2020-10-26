<?php

//region Namespace
namespace App\Functions;
//endregion

//region Using

//endregion

class Message
{
    public static function setMessage($type, $description)
    {
        $returnMessage = [
            'type' => null,
            'description' => null
        ];

        try {

            $returnMessage['type'] = $type;
            $returnMessage['description'] = $description;

        } catch (\Exception $e) {
            //region Log
            $this->container->logger->addCritical($e->getMessage());
            //endregion
        }

        return $returnMessage;
    }
}