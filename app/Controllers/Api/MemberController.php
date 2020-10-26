<?php

//region Namespace
namespace App\Controllers\Api;
//endregion

//region Using
use App\Config;
use App\Functions;
use App\Models\TblMember;
use Respect\Validation\Validator as v;
//endregion

class MemberController extends Controller
{
    public function getLogin($request, $response)
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

            //region Method Control
            if ($_SERVER['REQUEST_METHOD'] == 'GET') {

                //region Paramater Validation
                $validation = $this->validator->validate($request, [
                    'mail' => v::noWhitespace()->notEmpty()->length(2, 320),
                    'password' => v::noWhitespace()->notEmpty()
                ]);

                if ($validation->failed()) {
                    //region Set Return Data
                    $returnData["message"] = Functions\Message::setMessage(Config\Message\Types::INFO,Config\Message\General::FORM_FIELD_VALIDATION);
                    //endregion
                } else {
                    //region Main Variables
                    $mail = null;
                    $password = null;
                    //endregion

                    //region Request Control
                    if (null !== $request->getParam('mail')) {
                        $mail = Functions\General::clearFormInput($request->getParam('mail'));
                    }
                    if (null !== $request->getParam('password')) {
                        $password = $request->getParam('password');
                    }
                    //endregion

                    //region Member Control
                    $table = TblMember::where('mail', $mail)->orWhere('username', $mail)->orWhere('phone', $mail);

                    if (!empty($table) && !empty($table->get()) && !$table->get()->isEmpty()) {

                        //region Active Control
                        if ((bool)$table->is_active === true) {

                            //region Password Control
                            if (password_verify($password, $table->password)) {
                                //region Set Return Data
                                $returnData["message"] = Functions\Message::setMessage(Config\Message\Types::SUCCESS,null);
                                $returnData["table"] = $table->first();
                                //endregion
                            } else {

                                //region Set Return Data
                                $returnData["message"] = Functions\Message::setMessage(Config\Message\Types::INFO,Config\Message\General::WRONG_PASSWORD);
                                //endregion
                            }
                            //endregion

                        } else {
                            //region Set Return Data
                            $returnData["message"] = Functions\Message::setMessage(Config\Message\Types::INFO,Config\Message\General::ACCOUNT_PASSIVE);
                            //endregion
                        }
                        //endregion
                    } else {
                        $returnData["message"] = Functions\Message::setMessage(Config\Message\Types::INFO,Config\Message\General::NOTHING_MAIL);
                    }
                    //endregion
                }
                //endregion

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

    public function postRegister($request, $response)
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

            //region Method Control
            if ($_SERVER['REQUEST_METHOD'] == 'POST') {

                //region Paramater Validation
                $validation = $this->validator->validate($request, [
                    'name' => v::stringType()->notEmpty()->length(6,255),
                    'mail' => v::noWhitespace()->notEmpty()->email()->EmailAvailable()->length(5, 320),
                    'username' => v::stringType()->noWhitespace()->notEmpty(),
                    'phone' => v::numeric()->noWhitespace()->notEmpty()->length(null, 10),
                    'password' => v::noWhitespace()->notEmpty(),
                    'password_confirm' => v::noWhitespace()->notEmpty()
                ]);

                if ($validation->failed()) {

                    //region Set Return Data
                    $returnData["message"] = Functions\Message::setMessage(Config\Message\Types::INFO,Config\Message\General::FORM_FIELD_VALIDATION);
                    //endregion

                } else {
                    //region Main Variables
                    $name = null;
                    $mail = null;
                    $phone = null;
                    $username = null;
                    $password = null;
                    $passwordConfirm = null;
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
                        $password = $request->getParam('password');
                    }
                    if (null !== $request->getParam('password_confirm')) {
                        $passwordConfirm = $request->getParam('password_confirm');
                    }
                    //endregion

                    //region Table Create
                    $table = TblMember::create([
                        'name' => $name,
                        'mail' => $mail,
                        'username' => $username,
                        'phone' => $phone,
                        'password' => password_hash($password, PASSWORD_DEFAULT),
                        'is_active' => false
                    ]);
                    //endregion

                    //region Create Check and Return
                    if (!empty($table) && !empty($table->get()) && !$table->get()->isEmpty()) {
                        if ((int)$table->id > 0) {

                            //region Set Return Data
                            $returnData["message"] = Functions\Message::setMessage(Config\Message\Types::SUCCESS,NEW_MEMBER_SUCCESS);
                            $returnData["table"] = $table->first();
                            //endregion
                        }
                        else {

                            //region Set Return Data
                            $returnData["message"] = Functions\Message::setMessage(Config\Message\Types::ERROR,Config\Message\General::NEW_MEMBER_ERROR);
                            //endregion
                        }
                    } else {

                        //region Set Return Data
                        $returnData["message"] = Functions\Message::setMessage(Config\Message\Types::INFO,Config\Message\General::NEW_MEMBER_ERROR);
                        //endregion
                    }
                    //endregion
                }
                //endregion

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