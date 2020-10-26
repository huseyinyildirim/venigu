<?php

//region Namespace
namespace App\Controllers;
//endregion

//region Using
use App\Config;
use App\Functions;
use App\Models\TblMember;
use Carbon\Carbon;
use Respect\Validation\Validator as v;
//endregion

class MemberController extends Controller
{
    //region logout
    public function getLogout($request, $response)
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

            //region Logout
            $this->member->logout();
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

        //region Redirect
        return $response->withRedirect($this->router->pathFor('home'));
        //endregion
    }
    //endregion

    //region Change Password
    public function getChangePassword($request, $response)
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
        return $this->view->render($response, 'site/member/changepassword.twig', $returnData);
        //endregion
    }

    public function postChangePassword($request, $response)
    {
        //region Return Data
        $returnData = [
            'message' => null
        ];
        //endregion

        try {

            //region Paramater Validation
            $validation = $this->validator->validate($request, [
                'password_old' => v::noWhitespace()->notEmpty()->matchesPassword($this->member->user()->password),
                'password' => v::noWhitespace()->notEmpty(),
                'password_confirm' => v::noWhitespace()->notEmpty()
            ]);

            if ($validation->failed()) {
                //region Set Flash Message
                $this->flash->addMessage(Config\Flash::ERROR, Config\Message\General::FORM_FIELD_VALIDATION);
                //endregion

                return $response->withRedirect($this->router->pathFor('member-changepassword'));
            }
            //endregion

            //region Main Variables
            $password = null;
            //endregion

            //region Request Control
            if (null !== $request->getParam('password')) {
                $password = $request->getParam('password');
            }
            //endregion

            //region Table Create
            $table = TblMember::where('id', $this->container->member->user()->id)->update([
                'password' => password_hash($password, PASSWORD_DEFAULT),
                'updated_at' => Carbon::now()
            ]);
            //endregion

            //region Create Check and Return
            if (!empty($table)) {
                //region Set Flash Message
                $this->flash->addMessage(Config\Flash::SUCCESS, Config\Message\General::PASSWORD_CHANGE_SUCCESS);
                //endregion

                //region Set Return Data
                $returnData["message"] = Functions\Message::setMessage(Config\Message\Types::SUCCESS, null);
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
            return $response->withRedirect($this->router->pathFor('member-changepassword'));
            //endregion
        }

        return $response->withRedirect($this->router->pathFor('member-changepassword'));
    }
    //endregion
}