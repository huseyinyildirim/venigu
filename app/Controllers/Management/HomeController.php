<?php

//region Namespace
namespace App\Controllers\Management;
//endregion

//region Using
use App\Config;
use App\Functions;
//endregion

class HomeController extends Controller
{
    public function getIndex($request, $response)
    {
        //region Return Data
        $returnData = [
            'message' => null,
            'table' => null
        ];
        //endregion

        try {

            //region Variables
            //region Table Variables
            $table = null;
            //endregion
            //endregion

            //region Set Return Data
            $returnData["message"] = Functions\Message::setMessage(Config\Message\Types::SUCCESS,null);
            //endregion

        } catch (\Exception $e) {
            //region Log
            $this->logger->addCritical($e->getMessage());
            //endregion
            //region Set Flash Message
            $this->flash->addMessage(Config\Flash::ERROR, $e->getMessage());
            //endregion
            //region Set Return Data
            $returnData["message"] = Functions\Message::setMessage(Config\Message\Types::ERROR,$e->getMessage());
            //endregion
        }

        //region Render Page
        return $this->view->render($response, 'management/home/index.twig', $returnData);
        //endregion
    }
}