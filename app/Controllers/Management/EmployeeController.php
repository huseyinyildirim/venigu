<?php

//region Namespace
namespace App\Controllers\Management;
//endregion

//region Using
use App\Config;
use App\Functions;
use App\Models\TblEmployee;
use App\Models\TblEmployeeJob;
use Respect\Validation\Validator as v;
use Carbon\Carbon;
//endregion

class EmployeeController extends Controller
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
            $mainTable = null;
            //endregion
            //endregion

            //region Table
            $mainTable = new TblEmployee();
            //endregion

            //region Search
            if (null !== $request->getParam('word')) {
                //region Word Get
                $word = Functions\General::clearFormInput($request->getParam('word'));
                //endregion
                //region Like
                $mainTable = $mainTable->where('name', 'like','%'.$word.'%')->orWhere('mail', 'like','%'.$word.'%')->orWhere('username', 'like','%'.$word.'%')->orWhere('phone', 'like','%'.$word.'%');
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

                //region Jobs Added
                foreach ($paginationTable as $item){
                    $table[] = [
                        'Jobs' => TblEmployeeJob::where('employee_id', $item->id)->select('job_id')->get(),
                        'Employee' => $item
                    ];
                }
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
        return $this->view->render($response, 'management/employee/index.twig', $returnData);
        //endregion
    }

    public function getNew($request, $response)
    {
        //region Return Data
        $returnData = [
            'message' => null
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
        return $this->view->render($response, 'management/employee/new.twig', $returnData);
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
                'company_id' => v::numeric()->notEmpty(),
                'name' => v::stringType()->notEmpty()->length(6,255),
                'mail' => v::noWhitespace()->notEmpty()->email()->EmailAvailable()->length(5, 320),
                'phone' => v::numeric()->noWhitespace()->notEmpty()->PhoneAvailable()->length(null, 10),
                'password' => v::noWhitespace()->notEmpty(),
                'password_confirm' => v::noWhitespace()->notEmpty()
            ]);

            if ($validation->failed()) {
                //region Set Flash Message
                $this->flash->addMessage(Config\Flash::ERROR, Config\Message\General::FORM_FIELD_VALIDATION);
                //endregion

                return $response->withRedirect($this->router->pathFor('management-employee-new'));
            }
            //endregion

            //region Main Variables
            $companyId = null;
            $name = null;
            $mail = null;
            $phone = null;
            $password = null;
            $isActive = null;
            //endregion

            //region Request Control
            if (!empty($request->getParam('company_id'))) {
                $companyId = (int)$request->getParam('company_id');
            }
            if (null !== $request->getParam('name')) {
                $name = Functions\General::clearFormInput($request->getParam('name'));
            }
            if (null !== $request->getParam('mail')) {
                $mail = Functions\General::clearFormInput($request->getParam('mail'));
            }
            if (null !== $request->getParam('phone')) {
                $phone = Functions\General::clearFormInput($request->getParam('phone'));
            }
            if (null !== $request->getParam('password')) {
                $password=$request->getParam('password');
            }
            $isActive = Functions\TryParse::strToBool($request->getParam('is_active'));
            //endregion

            //region Table Create
            $table = TblEmployee::create([
                'company_id' => $companyId,
                'name' => $name,
                'mail' => $mail,
                'phone' => $phone,
                'password' => password_hash($password, PASSWORD_DEFAULT),
                'is_active' => $isActive,
                'created_by' => $this->container->management->admin()->id
            ]);
            //endregion

            //region Create Check and Return
            if (!empty($table) && !empty($table->get()) && !$table->get()->isEmpty()) {
                if ((int)$table->id > 0) {
                    //region Set Flash Message
                    $this->flash->addMessage(Config\Flash::SUCCESS, Config\Message\General::NEW_RECORD_SUCCESS);
                    //endregion

                    //region Set Return Data
                    $returnData["message"] = Functions\Message::setMessage(Config\Message\Types::SUCCESS,Config\Message\General::NEW_RECORD_SUCCESS);
                    //endregion

                    return $response->withRedirect($this->router->pathFor('management-employee'));
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
            return $response->withRedirect($this->router->pathFor('management-employee-new'));
            //endregion
        }

        return $response->withRedirect($this->router->pathFor('management-employee-new'));
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
                $table = TblEmployee::where('id', $id);
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
                    return $response->withRedirect($this->router->pathFor('management-employee'));
                    //endregion
                }
            } else {
                //region Set Flash Message
                $this->flash->addMessage(Config\Flash::ERROR, Config\Message\Emptys::NULL_ID);
                //endregion
                //region With Redirect
                return $response->withRedirect($this->router->pathFor('management-employee'));
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
            return $response->withRedirect($this->router->pathFor('management-employee'));
            //endregion
        }

        //region Render Page
        return $this->view->render($response, 'management/employee/edit.twig', $returnData);
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
                    'company_id' => v::numeric()->notEmpty(),
                    'name' => v::stringType()->notEmpty()->length(6,255),
                    'mail' => v::noWhitespace()->notEmpty()->email()->length(5, 320),
                    'phone' => v::numeric()->noWhitespace()->notEmpty()->length(null, 10),
                    'password' => v::noWhitespace(),
                    'password_confirm' => v::noWhitespace()
                ]);

                if ($validation->failed()) {
                    //region Set Flash Message
                    $this->flash->addMessage(Config\Flash::ERROR, Config\Message\General::FORM_FIELD_VALIDATION);
                    //endregion

                    return $response->withRedirect($this->router->pathFor('management-employee-edit', ['id' => $id]));
                }
                //endregion

                //region Main Variables
                $companyId = null;
                $name = null;
                $mail = null;
                $phone = null;
                $password = null;
                $isActive = null;
                //endregion

                //region Request Control
                if (!empty($request->getParam('company_id'))) {
                    $companyId = (int)$request->getParam('company_id');
                }
                if (null !== $request->getParam('name')) {
                    $name = Functions\General::clearFormInput($request->getParam('name'));
                }
                if (null !== $request->getParam('mail')) {
                    $mail = Functions\General::clearFormInput($request->getParam('mail'));
                }
                if (null !== $request->getParam('phone')) {
                    $phone = Functions\General::clearFormInput($request->getParam('phone'));
                }
                if (null !== $request->getParam('password')) {
                    $password=$request->getParam('password');
                }
                $isActive = Functions\TryParse::strToBool($request->getParam('is_active'));
                //endregion

                //region Table Update
                $data = [
                    'company_id' => $companyId,
                    'name' => $name,
                    'mail' => $mail,
                    'phone' => $phone,
                    'is_active' => $isActive,
                    'updated_at' => Carbon::now(),
                    'updated_by' => $this->container->management->admin()->id
                ];

                if(null !== $password){
                    $data['password'] = password_hash($password, PASSWORD_DEFAULT);
                }

                $table = TblEmployee::where('id', $id)->update($data);
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
                return $response->withRedirect($this->router->pathFor('management-employee'));
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
            return $response->withRedirect($this->router->pathFor('management-employee'));
            //endregion
        }

        return $response->withRedirect($this->router->pathFor('management-employee-edit', ['id' => $id]));
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

                $table = TblEmployee::where('id', $id)->delete();
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
                return $response->withRedirect($this->router->pathFor('management-employee'));
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
            return $response->withRedirect($this->router->pathFor('management-employee'));
            //endregion
        }

        return $response->withRedirect($this->router->pathFor('management-employee'));
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

                return $response->withRedirect($this->router->pathFor('management-employee'));
            }
            //endregion

            //region Main Variables
            $ids = null;
            //endregion

            //region Request Control
            $ids = $request->getParam('id');
            //endregion

            foreach ($ids as $id) {
                $table = TblEmployee::where('id', $id)->delete();
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
            return $response->withRedirect($this->router->pathFor('management-employee'));
            //endregion
        }

        return $response->withRedirect($this->router->pathFor('management-employee'));
    }
}