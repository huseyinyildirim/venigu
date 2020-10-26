<?php

//region Namespace
namespace App\Controllers;
//endregion

//region Using
use App\Config;
use App\Functions;
use App\Models\TblService;
use Respect\Validation\Validator as v;
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

            //region Table
            $table = new TblService();
            //endregion

            if (!empty($table) && !empty($table->get()) && !$table->get()->isEmpty()) {
                //region Set Return Data
                $returnData["message"] = Functions\Message::setMessage(Config\Message\Types::SUCCESS,null);
                $returnData["table"] = $table;
                //endregion
            } else {
                $returnData["message"] = Functions\Message::setMessage(Config\Message\Types::INFO,Config\Message\Emptys::TABLE);
            }

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
        return $this->view->render($response, 'site/home/index.twig', $returnData);
        //endregion
    }
}