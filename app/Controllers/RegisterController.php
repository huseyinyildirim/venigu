<?php

//region Namespace
namespace App\Controllers;
//endregion

//region Using
use App\Config;
use App\Functions;
use App\Models\TblMember;
use Respect\Validation\Validator as v;
//endregion

class RegisterController extends Controller
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
        return $this->view->render($response, 'site/register/index.twig', $returnData);
        //endregion
    }

    public function postIndex($request, $response)
    {
        //region Return Data
        $returnData = [
            'message' => null
        ];
        //endregion

        try {

            //region Paramater Validation
            $validation = $this->validator->validate($request, [
                'name' => v::notEmpty()->length(6,255),
                'mail' => v::noWhitespace()->notEmpty()->email()->EmailAvailable()->length(5, 320),
                'password' => v::noWhitespace()->notEmpty(),
                'password_confirm' => v::noWhitespace()->notEmpty()
            ]);

            if ($validation->failed()) {
                //region Set Flash Message
                $this->flash->addMessage(Config\Flash::ERROR, Config\Message\General::FORM_FIELD_VALIDATION);
                //endregion

                return $response->withRedirect($this->router->pathFor('register'));
            }
            //endregion

            //region Main Variables
            $name = null;
            $mail = null;
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
                'password' => password_hash($password, PASSWORD_DEFAULT),
                'is_active' => false
            ]);
            //endregion

            //region Create Check and Return
            if (!empty($table) && !empty($table->get()) && !$table->get()->isEmpty()) {
                if ((int)$table->id > 0) {
                    //region Set Flash Message
                    $this->flash->addMessage(Config\Flash::SUCCESS, Config\Message\General::NEW_MEMBER_SUCCESS);
                    //endregion

                    //region Set Return Data
                    $returnData["message"] = Functions\Message::setMessage(Config\Message\Types::SUCCESS,null);
                    //endregion

                    return $response->withRedirect($this->router->pathFor('register'));
                }
                else {
                    //region Set Flash Message
                    $this->flash->addMessage(Config\Flash::INFO, Config\Message\General::NEW_MEMBER_ERROR);
                    //endregion

                    //region Set Return Data
                    $returnData["message"] = Functions\Message::setMessage(Config\Message\Types::ERROR,Config\Message\General::NEW_MEMBER_ERROR);
                    //endregion
                }
            } else {
                //region Set Flash Message
                $this->flash->addMessage(Config\Flash::INFO, Config\Message\General::NEW_MEMBER_ERROR);
                //endregion

                //region Set Return Data
                $returnData["message"] = Functions\Message::setMessage(Config\Message\Types::ERROR,Config\Message\General::NEW_MEMBER_ERROR);
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
            return $response->withRedirect($this->router->pathFor('register'));
            //endregion
        }

        return $response->withRedirect($this->router->pathFor('register'));
    }
}