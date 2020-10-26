<?php

//region Namespace
namespace App\Controllers\Management;
//endregion

//region Using
use App\Config;
use App\Functions;
use App\Models\TblMember;
use Respect\Validation\Validator as v;
use Carbon\Carbon;
//endregion

class MemberController extends Controller
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
            $mainTable = new TblMember();
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
        return $this->view->render($response, 'management/member/index.twig', $returnData);
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
        return $this->view->render($response, 'management/member/new.twig', $returnData);
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
                'name' => v::stringType()->notEmpty()->length(6,255),
                'mail' => v::noWhitespace()->notEmpty()->email()->EmailAvailable()->length(5, 320),
                'username' => v::noWhitespace()->notEmpty()->UsernameAvailable()->length(null, 50),
                'phone' => v::numeric()->noWhitespace()->notEmpty()->PhoneAvailable()->length(null, 10),
                'password' => v::noWhitespace()->notEmpty(),
                'password_confirm' => v::noWhitespace()->notEmpty()
            ]);

            if ($validation->failed()) {
                //region Set Flash Message
                $this->flash->addMessage(Config\Flash::ERROR, Config\Message\General::FORM_FIELD_VALIDATION);
                //endregion

                return $response->withRedirect($this->router->pathFor('management-member-new'));
            }
            //endregion

            //region Main Variables
            $name = null;
            $mail = null;
            $username = null;
            $phone = null;
            $password = null;
            $isActive = null;
            //endregion

            //region Request Control
            if (null !== $request->getParam('name')) {
                $name = Functions\General::clearFormInput($request->getParam('name'));
            }
            if (null !== $request->getParam('mail')) {
                $mail = Functions\General::clearFormInput($request->getParam('mail'));
            }
            if (null !== $request->getParam('username')) {
                $username = Functions\General::clearFormInput($request->getParam('username'));
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
            $table = TblMember::create([
                'name' => $name,
                'mail' => $mail,
                'username' => $username,
                'phone' => $phone,
                'password' => password_hash($password, PASSWORD_DEFAULT),
                'is_active' => $isActive
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

                    return $response->withRedirect($this->router->pathFor('management-member'));
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
            return $response->withRedirect($this->router->pathFor('management-member-new'));
            //endregion
        }

        return $response->withRedirect($this->router->pathFor('management-member-new'));
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
                $mainTable = $this->db->table('tbl_member')->where('id', $id);
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
                    return $response->withRedirect($this->router->pathFor('management-member'));
                    //endregion
                }
            } else {
                //region Set Flash Message
                $this->flash->addMessage(Config\Flash::ERROR, Config\Message\Emptys::NULL_ID);
                //endregion
                //region With Redirect
                return $response->withRedirect($this->router->pathFor('management-member'));
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
            return $response->withRedirect($this->router->pathFor('management-member'));
            //endregion
        }

        //region Render Page
        return $this->view->render($response, 'management/member/edit.twig', $returnData);
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

                //TODO: E-posta kontrolü eklenecek
                //region Paramater Validation
                $validation = $this->validator->validate($request, [
                    'name' => v::stringType()->notEmpty()->length(6,255),
                    'mail' => v::noWhitespace()->notEmpty()->email()->length(5, 320),
                    'username' => v::noWhitespace()->notEmpty()->length(null, 50),
                    'phone' => v::numeric()->noWhitespace()->notEmpty()->length(null, 10),
                    'password' => v::noWhitespace(),
                    'password_confirm' => v::noWhitespace()
                ]);

                if ($validation->failed()) {
                    //region Set Flash Message
                    $this->flash->addMessage(Config\Flash::ERROR, Config\Message\General::FORM_FIELD_VALIDATION);
                    //endregion

                    return $response->withRedirect($this->router->pathFor('management-member-edit', ['id' => $id]));
                }
                //endregion

                //region Main Variables
                $name = null;
                $mail = null;
                $username = null;
                $phone = null;
                $password = null;
                $isActive = null;
                //endregion

                //region Request Control
                if (null !== $request->getParam('name')) {
                    $name = Functions\General::clearFormInput($request->getParam('name'));
                }
                if (null !== $request->getParam('mail')) {
                    $mail = Functions\General::clearFormInput($request->getParam('mail'));
                }
                if (null !== $request->getParam('username')) {
                    $username = Functions\General::clearFormInput($request->getParam('username'));
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
                    'name' => $name,
                    'mail' => $mail,
                    'username' => $username,
                    'phone' => $phone,
                    'is_active' => $isActive,
                    'updated_at' => Carbon::now()
                ];

                if(null !== $password){
                    $data['password'] = password_hash($password, PASSWORD_DEFAULT);
                }

                $table = TblMember::where('id', $id)->update($data);
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
                return $response->withRedirect($this->router->pathFor('management-member'));
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
            return $response->withRedirect($this->router->pathFor('management-member'));
            //endregion
        }

        return $response->withRedirect($this->router->pathFor('management-member-edit', ['id' => $id]));
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

                $table = TblMember::where('id', $id)->delete();
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
                return $response->withRedirect($this->router->pathFor('management-member'));
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
            return $response->withRedirect($this->router->pathFor('management-member'));
            //endregion
        }

        return $response->withRedirect($this->router->pathFor('management-member'));
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

                return $response->withRedirect($this->router->pathFor('management-member'));
            }
            //endregion

            //region Main Variables
            $ids = null;
            //endregion

            //region Request Control
            $ids = $request->getParam('id');
            //endregion

            foreach ($ids as $id) {
                $table = TblMember::where('id', $id)->delete();
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
            return $response->withRedirect($this->router->pathFor('management-member'));
            //endregion
        }

        return $response->withRedirect($this->router->pathFor('management-member'));
    }
}