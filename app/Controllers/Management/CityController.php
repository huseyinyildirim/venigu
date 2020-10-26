<?php

//region Namespace
namespace App\Controllers\Management;
//endregion

//region Using
use App\Config;
use App\Functions;
use App\Models\TblSetupCity;
use Respect\Validation\Validator as v;
use Carbon\Carbon;
//endregion

class CityController extends Controller
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
            $table = new TblSetupCity();
            //endregion

            //region Search
            if (null !== $request->getParam('word')) {
                //region Word Get
                $word = Functions\General::clearFormInput($request->getParam('word'));
                //endregion
                //region Like
                $table = $table->where('title', 'like','%'.$word.'%');
                //endregion
                //region Word Set in Return Data
                $returnData["search"] = $word;
                //endregion
            }
            //endregion

            if (!empty($table) && !empty($table->get()) && !$table->get()->isEmpty()) {
                //region Paging
                $page = ($request->getParam('page', 0) > 0) ? $request->getParam('page') : 1;
                $pageSize = Config\Base\Pagination::GRID_LIST_COUNT;
                $skip = ($page - 1) * $pageSize;
                $count = $table->count();

                $pagination = [
                    'needed' => $count > $pageSize,
                    'count' => $count,
                    'page' => $page,
                    'lastpage' => (ceil($count / $pageSize) == 0 ? 1 : ceil($count / $pageSize)),
                    'limit' => $pageSize,
                ];

                $table = $table->skip($skip)->take($pageSize)->get();
                //endregion

                //region Set Return Data
                $returnData["message"] = Functions\Message::setMessage(Config\Message\Types::SUCCESS,null);
                $returnData["pagination"] = $pagination;
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
        return $this->view->render($response, 'management/city/index.twig', $returnData);
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
        return $this->view->render($response, 'management/city/new.twig', $returnData);
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
                'country_id' => v::numeric()->notEmpty(),
                'title' => v::stringType()->notEmpty()->length(2,50),
                'plate_no' => v::numeric()->notEmpty()->length(null,2),
                'phone_code' => v::numeric()->notEmpty()->length(null, 7)
            ]);

            if ($validation->failed()) {
                //region Set Flash Message
                $this->flash->addMessage(Config\Flash::ERROR, Config\Message\General::FORM_FIELD_VALIDATION);
                //endregion

                return $response->withRedirect($this->router->pathFor('management-city-new'));
            }
            //endregion

            //region Main Variables
            $countryId = null;
            $title = null;
            $plateNo = null;
            $phoneCode = null;
            $isActive = null;
            //endregion

            //region Request Control
            if (!empty($request->getParam('country_id'))) {
                $countryId = (int)$request->getParam('country_id');
            }
            if (null !== $request->getParam('title')) {
                $title = Functions\General::clearFormInput($request->getParam('title'));
            }
            if (null !== $request->getParam('plate_no')) {
                $plateNo = Functions\General::clearFormInput($request->getParam('plate_no'));
            }
            if (null !== $request->getParam('phone_code')) {
                $phoneCode = Functions\General::clearFormInput($request->getParam('phone_code'));
            }
            $isActive = Functions\TryParse::strToBool($request->getParam('is_active'));
            //endregion

            //region Table Create
            $data = [
                'country_id' => $countryId,
                'title' => $title,
                'plate_no' => $plateNo,
                'phone_code' => $phoneCode,
                'is_active' => $isActive,
                'created_by' => $this->container->management->admin()->id
            ];

            $table = TblSetupCity::create($data);
            //endregion

            //region Create Check and Return
            if (!empty($table) && !empty($table->get()) && !$table->get()->isEmpty()) {
                if ((int)$table->id > 0) {
                    //region Set Flash Message
                    $this->flash->addMessage(Config\Flash::SUCCESS, Config\Message\General::NEW_RECORD_SUCCESS);
                    //endregion

                    //region Set Return Data
                    $returnData["message"] = Functions\Message::setMessage(Config\Message\Types::SUCCESS,null);
                    //endregion

                    return $response->withRedirect($this->router->pathFor('management-city'));
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
            return $response->withRedirect($this->router->pathFor('management-city-new'));
            //endregion
        }

        return $response->withRedirect($this->router->pathFor('management-city-new'));
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
                $mainTable = $this->db->table('tbl_setup_city')->where('id', $id);
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
                    return $response->withRedirect($this->router->pathFor('management-city'));
                    //endregion
                }
            } else {
                //region Set Flash Message
                $this->flash->addMessage(Config\Flash::ERROR, Config\Message\Emptys::NULL_ID);
                //endregion
                //region With Redirect
                return $response->withRedirect($this->router->pathFor('management-city'));
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
            return $response->withRedirect($this->router->pathFor('management-city'));
            //endregion
        }

        //region Render Page
        return $this->view->render($response, 'management/city/edit.twig', $returnData);
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
                    'country_id' => v::numeric()->notEmpty(),
                    'title' => v::stringType()->notEmpty()->length(2,50),
                    'plate_no' => v::numeric()->notEmpty()->length(null,2),
                    'phone_code' => v::numeric()->notEmpty()->length(null, 7)
                ]);

                if ($validation->failed()) {
                    //region Set Flash Message
                    $this->flash->addMessage(Config\Flash::ERROR, Config\Message\General::FORM_FIELD_VALIDATION);
                    //endregion

                    return $response->withRedirect($this->router->pathFor('management-city-edit', ['id' => $id]));
                }
                //endregion

                //region Main Variables
                $countryId = null;
                $title = null;
                $plateNo = null;
                $phoneCode = null;
                $isActive = null;
                //endregion

                //region Request Control
                if (!empty($request->getParam('country_id'))) {
                    $countryId = (int)$request->getParam('country_id');
                }
                if (null !== $request->getParam('title')) {
                    $title = Functions\General::clearFormInput($request->getParam('title'));
                }
                if (null !== $request->getParam('plate_no')) {
                    $plateNo = Functions\General::clearFormInput($request->getParam('plate_no'));
                }
                if (null !== $request->getParam('phone_code')) {
                    $phoneCode = Functions\General::clearFormInput($request->getParam('phone_code'));
                }
                $isActive = Functions\TryParse::strToBool($request->getParam('is_active'));
                //endregion

                //region Table Update
                $data = [
                    'country_id' => $countryId,
                    'title' => $title,
                    'plate_no' => $plateNo,
                    'phone_code' => $phoneCode,
                    'is_active' => $isActive,
                    'updated_at' => Carbon::now(),
                    'updated_by' => $this->container->management->admin()->id
                ];

                $table = TblSetupCity::where('id', $id)->update($data);
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
                return $response->withRedirect($this->router->pathFor('management-city'));
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
            return $response->withRedirect($this->router->pathFor('management-city'));
            //endregion
        }

        return $response->withRedirect($this->router->pathFor('management-city-edit', ['id' => $id]));
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

                $table = TblSetupCity::where('id', $id)->delete();
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
                return $response->withRedirect($this->router->pathFor('management-city'));
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
            return $response->withRedirect($this->router->pathFor('management-city'));
            //endregion
        }

        return $response->withRedirect($this->router->pathFor('management-city'));
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

                return $response->withRedirect($this->router->pathFor('management-city'));
            }
            //endregion

            //region Main Variables
            $ids = null;
            //endregion

            //region Request Control
            $ids = $request->getParam('id');
            //endregion

            foreach ($ids as $id) {
                $table = TblSetupCity::where('id', $id)->delete();
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
            return $response->withRedirect($this->router->pathFor('management-city'));
            //endregion
        }

        return $response->withRedirect($this->router->pathFor('management-city'));
    }
}