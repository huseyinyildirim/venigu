<?php

//region Namespace
namespace App\Controllers\Management;
//endregion

//region Using
use App\Config;
use App\Functions;
use App\Models\TblCompany;
use Respect\Validation\Validator as v;
use Carbon\Carbon;
//endregion

class CompanyController extends Controller
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
            $mainTable = new TblCompany();
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
        return $this->view->render($response, 'management/company/index.twig', $returnData);
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
        return $this->view->render($response, 'management/company/new.twig', $returnData);
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
                'title' => v::stringType()->notEmpty()->length(1,150)
            ]);

            if ($validation->failed()) {
                //region Set Flash Message
                $this->flash->addMessage(Config\Flash::ERROR, Config\Message\General::FORM_FIELD_VALIDATION);
                //endregion

                return $response->withRedirect($this->router->pathFor('management-company-new'));
            }
            //endregion

            //region Main Variables
            $title = null;
            $isActive = null;
            //endregion

            //region Request Control
            if (null !== $request->getParam('title')) {
                $title = Functions\General::clearFormInput($request->getParam('title'));
            }
            $isActive = Functions\TryParse::strToBool($request->getParam('is_active'));
            //endregion

            //region Table Create
            $data = [
                'title' => $title,
                'is_active' => $isActive,
                'created_by' => $this->container->management->admin()->id
            ];

            $table = TblCompany::create($data);
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

                    return $response->withRedirect($this->router->pathFor('management-company'));
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
            return $response->withRedirect($this->router->pathFor('management-company-new'));
            //endregion
        }

        return $response->withRedirect($this->router->pathFor('management-company-new'));
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
                $table = TblCompany::where('id', $id);
                //endregion

                if (!empty($table) && !empty($table->get()) && !$table->get()->isEmpty()) {
                    //region Set Return Data
                    $returnData["message"] = Functions\Message::setMessage(Config\Message\Types::SUCCESS,null);
                    $returnData["table"] = $table->first();
                    //endregion
                } else {
                    //region Set Flash Message
                    $this->flash->addMessage(Config\Flash::ERROR, Config\Message\Emptys::NULL_RECORD);
                    //endregion

                    //region With Redirect
                    return $response->withRedirect($this->router->pathFor('management-company'));
                    //endregion
                }
            } else {
                //region Set Flash Message
                $this->flash->addMessage(Config\Flash::ERROR, Config\Message\Emptys::NULL_ID);
                //endregion
                //region With Redirect
                return $response->withRedirect($this->router->pathFor('management-company'));
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
            return $response->withRedirect($this->router->pathFor('management-company'));
            //endregion
        }

        //region Render Page
        return $this->view->render($response, 'management/company/edit.twig', $returnData);
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
                    'title' => v::stringType()->notEmpty()->length(2,150)
                ]);

                if ($validation->failed()) {
                    //region Set Flash Message
                    $this->flash->addMessage(Config\Flash::ERROR, Config\Message\General::FORM_FIELD_VALIDATION);
                    //endregion

                    return $response->withRedirect($this->router->pathFor('management-company-edit', ['id' => $id]));
                }
                //endregion

                //region Main Variables
                $title = null;
                $isActive = null;
                //endregion

                //region Request Control
                if (null !== $request->getParam('title')) {
                    $title = Functions\General::clearFormInput($request->getParam('title'));
                }
                $isActive = Functions\TryParse::strToBool($request->getParam('is_active'));
                //endregion

                //region Table Update
                $data = [
                    'title' => $title,
                    'is_active' => $isActive,
                    'updated_at' => Carbon::now(),
                    'updated_by' => $this->container->management->admin()->id
                ];

                $table = TblCompany::where('id', $id)->update($data);
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
                return $response->withRedirect($this->router->pathFor('management-company'));
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
            return $response->withRedirect($this->router->pathFor('management-company'));
            //endregion
        }

        return $response->withRedirect($this->router->pathFor('management-company-edit', ['id' => $id]));
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

                $table = TblCompany::where('id', $id)->delete();
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
                return $response->withRedirect($this->router->pathFor('management-company'));
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
            return $response->withRedirect($this->router->pathFor('management-company'));
            //endregion
        }

        return $response->withRedirect($this->router->pathFor('management-company'));
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

                return $response->withRedirect($this->router->pathFor('management-company'));
            }
            //endregion

            //region Main Variables
            $ids = null;
            //endregion

            //region Request Control
            $ids = $request->getParam('id');
            //endregion

            foreach ($ids as $id) {
                $table = TblCompany::where('id', $id)->delete();
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
            return $response->withRedirect($this->router->pathFor('management-company'));
            //endregion
        }

        return $response->withRedirect($this->router->pathFor('management-company'));
    }
}