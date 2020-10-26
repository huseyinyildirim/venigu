<?php

//region Namespace
namespace App\Controllers\Management;
//endregion

//region Using
use App\Config;
use App\Functions;
use App\Models\TblService;
use App\Models\TblServiceField;
use Respect\Validation\Validator as v;
use Carbon\Carbon;
//endregion

class ServiceController extends Controller
{
    public function getIndex($request, $response)
    {
        //region Return Data
        $returnData = [
            'message' => null,
            'pagination' => null,
            'table' => null,
            'search' => null
        ];
        //endregion

        try {

            //region Variables
            //region Paging
            $page = 0;
            $pageSize = 0;
            $skip = 0 ;
            $count = 0;
            $pagination = null;
            //endregion
            //region Table Variables
            $table = null;
            //endregion
            //endregion

            //region Table
            $mainTable = new TblService();
            //endregion

            //region Search
            if (null !== $request->getParam('word')) {
                //region Word Get
                $word = Functions\General::clearFormInput($request->getParam('word'));
                //endregion
                //region Like
                $mainTable = $mainTable->where('title', 'like','%'.$word.'%');
                //endregion
                //region Word Set in Return Data
                $returnData["search"] = $word;
                //endregion
            }
            //endregion

            if (!empty($mainTable) && !empty($mainTable->get()) && !$mainTable->get()->isEmpty()) {
                //region Paging
                $page = ($request->getParam('page', 0) > 0) ? $request->getParam('page') : 1;
                $pageSize = Config\Base\Pagination::GRID_LIST_COUNT;
                $skip = ($page - 1) * $pageSize;
                $count = $mainTable->count();

                $pagination = [
                    'needed' => $count > $pageSize,
                    'count' => $count,
                    'page' => $page,
                    'lastpage' => (ceil($count / $pageSize) == 0 ? 1 : ceil($count / $pageSize)),
                    'limit' => $pageSize,
                ];

                $paginationTable = $mainTable->skip($skip)->take($pageSize)->get();
                //endregion

                //region Set Return Data
                $returnData["message"] = Functions\Message::setMessage(Config\Message\Types::SUCCESS,null);
                $returnData["pagination"] = $pagination;
                $returnData["table"] = $paginationTable;
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
        return $this->view->render($response, 'management/service/index.twig', $returnData);
        //endregion
    }

    public function getNew($request, $response)
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
        return $this->view->render($response, 'management/service/new.twig', $returnData);
        //endregion
    }

    public function postNew($request, $response)
    {
        //region Return Data
        $returnData = [
            'message' => null
        ];
        //endregion

        try {

            //region Paramater Validation
            $validation = $this->validator->validate($request, [
                'title' => v::stringType()->notEmpty()->length(2,50),
                'desc' => v::stringType()->length(null, 500)
            ]);

            if ($validation->failed()) {
                //region Set Flash Message
                $this->flash->addMessage(Config\Flash::ERROR, Config\Message\General::FORM_FIELD_VALIDATION);
                //endregion

                return $response->withRedirect($this->router->pathFor('management-service-new'));
            }
            //endregion

            //region Main Variables
            $title = null;
            $desc = null;
            $isActive = null;
            //endregion

            //region Request Control
            if (null !== $request->getParam('title')) {
                $title = Functions\General::clearFormInput($request->getParam('title'));
            }
            if (null !== $request->getParam('desc')) {
                $desc = Functions\General::clearFormInput($request->getParam('desc'));
            }
            $isActive = Functions\TryParse::strToBool($request->getParam('is_active'));
            //endregion

            //region Table Create
            $data = [
                'title' => $title,
                'desc' => $desc,
                'is_active' => $isActive,
                'created_by' => $this->container->management->admin()->id
            ];

            $table = TblService::create($data);
            //endregion



            //region Create Check and Return
            if (!empty($table) && !empty($table->get()) && !$table->get()->isEmpty()) {
                if ((int)$table->id > 0) {

                    //region Service Field
                    $fieldTable = TblServiceField::create([
                        'service_id' => $table->id
                    ]);
                    //endregion

                    //region Set Flash Message
                    $this->flash->addMessage(Config\Flash::SUCCESS, Config\Message\General::NEW_RECORD_SUCCESS);
                    //endregion

                    //region Set Return Data
                    $returnData["message"] = Functions\Message::setMessage(Config\Message\Types::SUCCESS,null);
                    //endregion

                    return $response->withRedirect($this->router->pathFor('management-service'));
                }
                else {
                    //region Set Flash Message
                    $this->flash->addMessage(Config\Flash::ERROR, Config\Message\General::NEW_RECORD_ERROR);
                    //endregion

                    //region Set Return Data
                    $returnData["message"] = Functions\Message::setMessage(Config\Message\Types::ERROR,Config\Message\General::NEW_RECORD_ERROR);
                    //endregion
                }
            } else {
                //region Set Flash Message
                $this->flash->addMessage(Config\Flash::ERROR, Config\Message\General::NEW_RECORD_ERROR);
                //endregion

                //region Set Return Data
                $returnData["message"] = Functions\Message::setMessage(Config\Message\Types::ERROR,Config\Message\General::NEW_RECORD_ERROR);
                //endregion
            }
            //endregion
        } catch (Exception $e) {
            //region Log
            $this->logger->addCritical($e->getMessage());
            //endregion
            //region Set Flash Message
            $this->flash->addMessage(Config\Flash::ERROR, $e->getMessage());
            //endregion
            //region Set Return Data
            $returnData["message"] = Functions\Message::setMessage(Config\Message\Types::ERROR,$e->getMessage());
            //endregion
            //region Redirect
            return $response->withRedirect($this->router->pathFor('management-service-new'));
            //endregion
        }

        return $response->withRedirect($this->router->pathFor('management-service-new'));
    }

    public function getEdit($request, $response, $args)
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
            //region Args Variables
            $id = null;
            //endregion
            //endregion

            //region Args Control
            if (!is_null($args['id'])) {
                //region Args Set
                $id = (int)$args['id'];
                //endregion

                //region Table
                $mainTable = $this->db->table('tbl_service')->where('id', $id);
                //endregion

                if (!empty($mainTable) && !empty($mainTable->get()) && !$mainTable->get()->isEmpty()) {
                    //region Set Return Data
                    $returnData["message"] = Functions\Message::setMessage(Config\Message\Types::SUCCESS,null);
                    $returnData["table"] = $mainTable->first();
                    //endregion
                } else {
                    //region Set Flash Message
                    $this->flash->addMessage(Config\Flash::ERROR, Config\Message\Emptys::NULL_RECORD);
                    //endregion

                    //region With Redirect
                    return $response->withRedirect($this->router->pathFor('management-service'));
                    //endregion
                }
            } else {
                //region Set Flash Message
                $this->flash->addMessage(Config\Flash::ERROR, Config\Message\Emptys::NULL_ID);
                //endregion
                //region With Redirect
                return $response->withRedirect($this->router->pathFor('management-service'));
                //endregion
            }
        } catch (\Exception $e) {
            //region Log
            $this->logger->addCritical($e->getMessage());
            //endregion
            //region Set Flash Message
            $this->flash->addMessage(Config\Flash::ERROR, $e->getMessage());
            //endregion
            //region With Redirect
            return $response->withRedirect($this->router->pathFor('management-service'));
            //endregion
        }

        //region Render Page
        return $this->view->render($response, 'management/service/edit.twig', $returnData);
        //endregion
    }

    public function postEdit($request, $response, $args)
    {
        //region Return Data
        $returnData = [
            'message' => null
        ];
        //endregion

        //region Args Variables
        $id = null;
        //endregion

        try {
            //region Args Control
            if (!is_null($args['id'])) {
                //region Args Set
                $id = (int)$args['id'];
                //endregion

                //region Paramater Validation
                $validation = $this->validator->validate($request, [
                    'title' => v::stringType()->notEmpty()->length(2,50),
                    'desc' => v::stringType()->length(null, 500)
                ]);

                if ($validation->failed()) {
                    //region Set Flash Message
                    $this->flash->addMessage(Config\Flash::ERROR, Config\Message\General::FORM_FIELD_VALIDATION);
                    //endregion

                    return $response->withRedirect($this->router->pathFor('management-service-edit', ['id' => $id]));
                }
                //endregion

                //region Main Variables
                $title = null;
                $desc = null;
                $isActive = null;
                //endregion

                //region Request Control
                if (null !== $request->getParam('title')) {
                    $title = Functions\General::clearFormInput($request->getParam('title'));
                }
                if (null !== $request->getParam('desc')) {
                    $desc = Functions\General::clearFormInput($request->getParam('desc'));
                }
                $isActive = Functions\TryParse::strToBool($request->getParam('is_active'));
                //endregion

                //region Table Update
                $data = [
                    'title' => $title,
                    'desc' => $desc,
                    'is_active' => $isActive,
                    'updated_at' => Carbon::now(),
                    'updated_by' => $this->container->management->admin()->id
                ];

                $table = TblService::where('id', $id)->update($data);
                //endregion

                //region Create Check and Return
                if (!empty($table)) {
                        //region Set Flash Message
                        $this->flash->addMessage(Config\Flash::SUCCESS, Config\Message\General::UPDATE_RECORD_SUCCESS);
                        //endregion

                        //region Set Return Data
                        $returnData["message"] = Functions\Message::setMessage(Config\Message\Types::SUCCESS,null);
                        //endregion
                } else {
                    //region Set Flash Message
                    $this->flash->addMessage(Config\Flash::INFO, Config\Message\General::NOTHING_CHANGE);
                    //endregion

                    //region Set Return Data
                    $returnData["message"] = Functions\Message::setMessage(Config\Message\Types::INFO,Config\Message\General::NOTHING_CHANGE);
                    //endregion
                }
                //endregion
            } else {
                //region Set Flash Message
                $this->flash->addMessage(Config\Flash::ERROR, Config\Message\Emptys::NULL_ID);
                //endregion

                //region With Redirect
                return $response->withRedirect($this->router->pathFor('management-service'));
                //endregion
            }
            //endregion
        } catch (Exception $e) {
            //region Log
            $this->logger->addCritical($e->getMessage());
            //endregion
            //region Set Flash Message
            $this->flash->addMessage(Config\Flash::ERROR, $e->getMessage());
            //endregion
            //region Set Return Data
            $returnData["message"] = Functions\Message::setMessage(Config\Message\Types::ERROR,$e->getMessage());
            //endregion
            //region Redirect
            return $response->withRedirect($this->router->pathFor('management-service'));
            //endregion
        }

        return $response->withRedirect($this->router->pathFor('management-service-edit', ['id' => $id]));
    }

    public function getFieldEdit($request, $response, $args)
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
            //region Args Variables
            $id = null;
            //endregion
            //endregion

            //region Args Control
            if (!is_null($args['id'])) {
                //region Args Set
                $id = (int)$args['id'];
                //endregion

                //region Table
                $mainTable = TblServiceField::where('service_id', $id);
                //endregion

                if (!empty($mainTable) && !empty($mainTable->get()) && !$mainTable->get()->isEmpty()) {
                    //region Set Return Data
                    $returnData["message"] = Functions\Message::setMessage(Config\Message\Types::SUCCESS,null);
                    $returnData["table"] = $mainTable->first();
                    //endregion
                } else {
                    //region Set Flash Message
                    $this->flash->addMessage(Config\Flash::ERROR, Config\Message\Emptys::NULL_RECORD);
                    //endregion

                    //region With Redirect
                    return $response->withRedirect($this->router->pathFor('management-service'));
                    //endregion
                }
            } else {
                //region Set Flash Message
                $this->flash->addMessage(Config\Flash::ERROR, Config\Message\Emptys::NULL_ID);
                //endregion
                //region With Redirect
                return $response->withRedirect($this->router->pathFor('management-service'));
                //endregion
            }
        } catch (\Exception $e) {
            //region Log
            $this->logger->addCritical($e->getMessage());
            //endregion
            //region Set Flash Message
            $this->flash->addMessage(Config\Flash::ERROR, $e->getMessage());
            //endregion
            //region With Redirect
            return $response->withRedirect($this->router->pathFor('management-service'));
            //endregion
        }

        //region Render Page
        return $this->view->render($response, 'management/service/field.twig', $returnData);
        //endregion
    }

    public function postFieldEdit($request, $response, $args)
    {
        //region Return Data
        $returnData = [
            'message' => null
        ];
        //endregion

        //region Args Variables
        $id = null;
        //endregion

        try {
            //region Args Control
            if (!is_null($args['id'])) {
                //region Args Set
                $id = (int)$args['id'];
                //endregion

                //region Paramater Validation
                $validation = $this->validator->validate($request, [
                ]);

                if ($validation->failed()) {
                    //region Set Flash Message
                    $this->flash->addMessage(Config\Flash::ERROR, Config\Message\General::FORM_FIELD_VALIDATION);
                    //endregion

                    return $response->withRedirect($this->router->pathFor('management-service-edit', ['id' => $id]));
                }
                //endregion

                //region Main Variables
                $detail = null;

                $startDate = null;
                $startTime = null;

                $startAddress = null;
                $startCountryId = null;
                $startCityId = null;
                $startTownId = null;
                $startDistrictId = null;

                $targetAddress = null;
                $targetCountryId = null;
                $targetCityId = null;
                $targetTownId = null;
                $targetDistrictId = null;

                $deliveryAddress = null;
                $deliveryCountryId = null;
                $deliveryCityId = null;
                $deliveryTownId = null;
                $deliveryDistrictId = null;

                $note = null;
                //endregion

                //region Request Control
                $detail = Functions\TryParse::strToBool($request->getParam('detail'));

                $startDate = Functions\TryParse::strToBool($request->getParam('start_date'));
                $startTime = Functions\TryParse::strToBool($request->getParam('start_time'));

                $startAddress = Functions\TryParse::strToBool($request->getParam('start_address'));
                $startCountryId = Functions\TryParse::strToBool($request->getParam('start_country_id'));
                $startCityId = Functions\TryParse::strToBool($request->getParam('start_city_id'));
                $startTownId = Functions\TryParse::strToBool($request->getParam('start_town_id'));
                $startDistrictId = Functions\TryParse::strToBool($request->getParam('start_district_id'));

                $targetAddress = Functions\TryParse::strToBool($request->getParam('target_address'));
                $targetCountryId = Functions\TryParse::strToBool($request->getParam('target_country_id'));
                $targetCityId = Functions\TryParse::strToBool($request->getParam('target_city_id'));
                $targetTownId = Functions\TryParse::strToBool($request->getParam('target_town_id'));
                $targetDistrictId = Functions\TryParse::strToBool($request->getParam('target_district_id'));

                $deliveryAddress = Functions\TryParse::strToBool($request->getParam('delivery_address'));
                $deliveryCountryId = Functions\TryParse::strToBool($request->getParam('delivery_country_id'));
                $deliveryCityId = Functions\TryParse::strToBool($request->getParam('delivery_city_id'));
                $deliveryTownId = Functions\TryParse::strToBool($request->getParam('delivery_town_id'));
                $deliveryDistrictId = Functions\TryParse::strToBool($request->getParam('delivery_district_id'));

                $note = Functions\TryParse::strToBool($request->getParam('note'));
                //endregion

                //region Table Update
                $data = [
                    'detail' => $detail,

                    'start_date' => $startDate,
                    'start_time' => $startTime,

                    'start_address' => $startAddress,
                    'start_country_id' => $startCountryId,
                    'start_city_id' => $startCityId,
                    'start_town_id' => $startTownId,
                    'start_district_id' => $startDistrictId,

                    'target_address' => $targetAddress,
                    'target_country_id' => $targetCountryId,
                    'target_city_id' => $targetCityId,
                    'target_town_id' => $targetTownId,
                    'target_district_id' => $targetDistrictId,

                    'delivery_address' => $deliveryAddress,
                    'delivery_country_id' => $deliveryCountryId,
                    'delivery_city_id' => $deliveryCityId,
                    'delivery_town_id' => $deliveryTownId,
                    'delivery_district_id' => $deliveryDistrictId,

                    'note' => $note,

                    'updated_at' => Carbon::now(),
                    'updated_by' => $this->container->management->admin()->id
                ];

                $table = TblServiceField::where('service_id', $id)->update($data);
                //endregion

                //region Create Check and Return
                if (!empty($table)) {
                    //region Set Flash Message
                    $this->flash->addMessage(Config\Flash::SUCCESS, Config\Message\General::UPDATE_RECORD_SUCCESS);
                    //endregion

                    //region Set Return Data
                    $returnData["message"] = Functions\Message::setMessage(Config\Message\Types::SUCCESS,null);
                    //endregion
                } else {
                    //region Set Flash Message
                    $this->flash->addMessage(Config\Flash::INFO, Config\Message\General::NOTHING_CHANGE);
                    //endregion

                    //region Set Return Data
                    $returnData["message"] = Functions\Message::setMessage(Config\Message\Types::INFO,Config\Message\General::NOTHING_CHANGE);
                    //endregion
                }
                //endregion
            } else {
                //region Set Flash Message
                $this->flash->addMessage(Config\Flash::ERROR, Config\Message\Emptys::NULL_ID);
                //endregion

                //region With Redirect
                return $response->withRedirect($this->router->pathFor('management-service'));
                //endregion
            }
            //endregion
        } catch (Exception $e) {
            //region Log
            $this->logger->addCritical($e->getMessage());
            //endregion
            //region Set Flash Message
            $this->flash->addMessage(Config\Flash::ERROR, $e->getMessage());
            //endregion
            //region Set Return Data
            $returnData["message"] = Functions\Message::setMessage(Config\Message\Types::ERROR,$e->getMessage());
            //endregion
            //region Redirect
            return $response->withRedirect($this->router->pathFor('management-service'));
            //endregion
        }

        return $response->withRedirect($this->router->pathFor('management-service-field', ['id' => $id]));
    }

    public function getDelete($request, $response, $args)
    {
        //region Return Data
        $returnData = [
            'message' => null
        ];
        //endregion

        //region Args Variables
        $id = null;
        //endregion

        try {
            //region Args Control
            if (!is_null($args['id'])) {
                //region Args Set
                $id = (int)$args['id'];
                //endregion

                $table = TblService::where('id', $id)->delete();
                //endregion

                //region Create Check and Return
                if (!empty($table)) {
                    //region Set Flash Message
                    $this->flash->addMessage(Config\Flash::SUCCESS, Config\Message\General::DELETE_RECORD_SUCCESS);
                    //endregion

                    //region Set Return Data
                    $returnData["message"] = Functions\Message::setMessage(Config\Message\Types::SUCCESS,null);
                    //endregion
                } else {
                    //region Set Flash Message
                    $this->flash->addMessage(Config\Flash::INFO, Config\Message\General::NOTHING_DELETE);
                    //endregion

                    //region Set Return Data
                    $returnData["message"] = Functions\Message::setMessage(Config\Message\Types::INFO,Config\Message\General::NOTHING_DELETE);
                    //endregion
                }
                //endregion
            } else {
                //region Set Flash Message
                $this->flash->addMessage(Config\Flash::ERROR, Config\Message\Emptys::NULL_ID);
                //endregion

                //region With Redirect
                return $response->withRedirect($this->router->pathFor('management-service'));
                //endregion
            }
            //endregion
        } catch (Exception $e) {
            //region Log
            $this->logger->addCritical($e->getMessage());
            //endregion
            //region Set Flash Message
            $this->flash->addMessage(Config\Flash::ERROR, $e->getMessage());
            //endregion
            //region Set Return Data
            $returnData["message"] = Functions\Message::setMessage(Config\Message\Types::ERROR,$e->getMessage());
            //endregion
            //region Redirect
            return $response->withRedirect($this->router->pathFor('management-service'));
            //endregion
        }

        return $response->withRedirect($this->router->pathFor('management-service'));
    }

    public function postAllDelete($request, $response)
    {
        //region Return Data
        $returnData = [
            'message' => null
        ];
        //endregion

        try {

            //region Paramater Validation
            $validation = $this->validator->validate($request, [
                'id' => v::arrayType()->notEmpty()
            ]);

            if ($validation->failed()) {
                //region Set Flash Message
                $this->flash->addMessage(Config\Flash::ERROR, Config\Message\General::FORM_FIELD_VALIDATION);
                //endregion

                return $response->withRedirect($this->router->pathFor('management-service'));
            }
            //endregion

            //region Main Variables
            $ids = null;
            //endregion

            //region Request Control
            $ids = $request->getParam('id');
            //endregion

            foreach ($ids as $id) {
                $table = TblService::where('id', $id)->delete();
            }

                //region Create Check and Return
                if (!empty($table)) {
                    //region Set Flash Message
                    $this->flash->addMessage(Config\Flash::SUCCESS, Config\Message\General::DELETE_RECORD_SUCCESS);
                    //endregion

                    //region Set Return Data
                    $returnData["message"] = Functions\Message::setMessage(Config\Message\Types::SUCCESS,null);
                    //endregion
                } else {
                    //region Set Flash Message
                    $this->flash->addMessage(Config\Flash::INFO, Config\Message\General::NOTHING_DELETE);
                    //endregion

                    //region Set Return Data
                    $returnData["message"] = Functions\Message::setMessage(Config\Message\Types::INFO,Config\Message\General::NOTHING_DELETE);
                    //endregion
                }
                //endregion

        } catch (Exception $e) {
            //region Log
            $this->logger->addCritical($e->getMessage());
            //endregion
            //region Set Flash Message
            $this->flash->addMessage(Config\Flash::ERROR, $e->getMessage());
            //endregion
            //region Set Return Data
            $returnData["message"] = Functions\Message::setMessage(Config\Message\Types::ERROR,$e->getMessage());
            //endregion
            //region Redirect
            return $response->withRedirect($this->router->pathFor('management-service'));
            //endregion
        }

        return $response->withRedirect($this->router->pathFor('management-service'));
    }
}