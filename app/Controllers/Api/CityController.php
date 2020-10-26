<?php

//region Namespace
namespace App\Controllers\Api;
//endregion

//region Using
use App\Config;
use App\Functions;
use App\Models\TblSetupCity;
//endregion

class CityController extends Controller
{
    public function getList($request, $response, $args)
    {
        //region Return Data
        $returnData = [
            'message' => null,
            'table' => null
        ];
        //endregion

        try {

            //region Variables
            //region Args Variables
            $id = null;
            //endregion
            //region Table Variables
            $table = null;
            //endregion
            //endregion

            //region Method Control
            if ($_SERVER['REQUEST_METHOD'] == 'GET') {

                //region Args Set
                $id = (int)$args['id'];
                //endregion

                if (!is_null($id)) {
                    //region Table
                    $table = TblSetupCity::where('country_id', $id);
                    //endregion

                    if (!empty($table) && !empty($table->get()) && !$table->get()->isEmpty()) {
                        //region Set Return Data
                        $returnData["message"] = Functions\Message::setMessage(Config\Message\Types::SUCCESS,null);
                        $returnData["table"] = $table->get();
                        //endregion
                    } else {
                        $returnData["message"] = Functions\Message::setMessage(Config\Message\Types::INFO,Config\Message\Emptys::TABLE);
                    }
                } else {
                    $returnData["message"] = Functions\Message::setMessage(Config\Message\Types::INFO,Config\Message\Emptys::TABLE);
                }

            } else {
                $returnData["message"] = Functions\Message::setMessage(Config\Message\Types::ERROR,Config\Message\General::ROUTE_PARAM_ERROR);
            }
            //endregion

        } catch (\Exception $e) {
            //region Log
            $this->logger->addCritical($e->getMessage());
            //endregion
            //region Set Return Data
            $returnData["message"] = Functions\Message::setMessage(Config\Message\Types::ERROR,$e->getMessage());
            //endregion
        }

        //region Render Page
        return $this->response->withJson($returnData);
        //endregion
    }

    public function getRow($request, $response, $args)
    {
        //region Return Data
        $returnData = [
            'message' => null,
            'table' => null
        ];
        //endregion

        try {

            //region Variables
            //region Args Variables
            $id = null;
            //endregion
            //region Table Variables
            $table = null;
            //endregion
            //endregion

            //region Method Control
            if ($_SERVER['REQUEST_METHOD'] == 'GET') {

                //region Args Set
                $id = (int)$args['id'];
                //endregion

                if (!is_null($id)) {
                    //region Table
                    $table = TblSetupCity::where('id', $id);
                    //endregion

                    if (!empty($table) && !empty($table->get()) && !$table->get()->isEmpty()) {
                        //region Set Return Data
                        $returnData["message"] = Functions\Message::setMessage(Config\Message\Types::SUCCESS,null);
                        $returnData["table"] = $table->first();
                        //endregion
                    } else {
                        $returnData["message"] = Functions\Message::setMessage(Config\Message\Types::INFO,Config\Message\Emptys::TABLE);
                    }
                } else {
                    $returnData["message"] = Functions\Message::setMessage(Config\Message\Types::INFO,Config\Message\Emptys::TABLE);
                }

            } else {
                $returnData["message"] = Functions\Message::setMessage(Config\Message\Types::ERROR,Config\Message\General::ROUTE_PARAM_ERROR);
            }
            //endregion

        } catch (\Exception $e) {
            //region Log
            $this->logger->addCritical($e->getMessage());
            //endregion
            //region Set Return Data
            $returnData["message"] = Functions\Message::setMessage(Config\Message\Types::ERROR,$e->getMessage());
            //endregion
        }

        //region Render Page
        return $this->response->withJson($returnData);
        //endregion
    }
}