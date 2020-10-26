<?php

//region Namespace
namespace App\Controllers\Management;
//endregion

//region Using
use App\Config;
use App\Functions;
use App\Models\TblAdmin;
use Respect\Validation\Validator as v;
//endregion

class LoginController extends Controller
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
            $returnData["message"] = Functions\Message::setMessage(Config\Message\Types::SUCCESS, null);
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
        return $this->view->render($response, 'management/login/index.twig', $returnData);
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
                'mail' => v::noWhitespace()->notEmpty()->email()->length(5, 320),
                'password' => v::noWhitespace()->notEmpty()
            ]);

            if ($validation->failed()) {
                //region Set Flash Message
                $this->flash->addMessage(Config\Flash::ERROR, Config\Message\General::FORM_FIELD_VALIDATION);
                //endregion
                //region Redirect
                return $response->withRedirect($this->router->pathFor('management-login'));
                //endregion
            }
            //endregion

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

            //region Table Create
            $table = TblAdmin::where('mail', $mail)->first();
            //endregion

            //region Create Check and Return
            if (!$table) {
                //region Set Flash Message
                $this->flash->addMessage(Config\Flash::INFO, Config\Message\General::NOTHING_MAIL);
                //endregion

                //region Set Return Data
                $returnData["message"] = Functions\Message::setMessage(Config\Message\Types::INFO,Config\Message\General::NOTHING_MAIL);
                //endregion

                //region Redirect
                return $response->withRedirect($this->router->pathFor('management-login'));
                //endregion
            }

            if ((bool)$table->is_active === false) {
                //region Set Flash Message
                $this->flash->addMessage(Config\Flash::INFO, Config\Message\General::ACCOUNT_PASSIVE);
                //endregion

                //region Set Return Data
                $returnData["message"] = Functions\Message::setMessage(Config\Message\Types::INFO,Config\Message\General::ACCOUNT_PASSIVE);
                //endregion

                //region Redirect
                return $response->withRedirect($this->router->pathFor('management-login'));
                //endregion
            }

            if (password_verify($password, $table->password)) {
                //region Session Set
                $_SESSION['management'] = $table->id;
                //endregion

                //region Set Flash Message
                $this->flash->addMessage(Config\Flash::SUCCESS, Config\Message\General::SUCCESS_LOGIN);
                //endregion

                //region Set Return Data
                $returnData["message"] = Functions\Message::setMessage(Config\Message\Types::SUCCESS,null);
                //endregion
            } else {
                //region Set Flash Message
                $this->flash->addMessage(Config\Flash::INFO, Config\Message\General::WRONG_PASSWORD);
                //endregion

                //region Set Return Data
                $returnData["message"] = Functions\Message::setMessage(Config\Message\Types::INFO,Config\Message\General::WRONG_PASSWORD);
                //endregion

                //region Redirect
                return $response->withRedirect($this->router->pathFor('management-login'));
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
            return $response->withRedirect($this->router->pathFor('management-login'));
            //endregion
        }

        return $response->withRedirect($this->router->pathFor('management'));
    }
}