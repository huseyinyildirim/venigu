<?php

//region Namespace
namespace App\Controllers\Management;
//endregion

//region Using
use App\Config;
use App\Functions;
use App\Models\TblSetupSocial;
use Respect\Validation\Validator as v;
use Carbon\Carbon;
//endregion

class SocialController extends Controller
{
    public function getEdit($request, $response)
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

            //region Table
            $table = TblSetupSocial::where('id', 1);
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
                return $response->withRedirect($this->router->pathFor('management-social'));
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
            return $response->withRedirect($this->router->pathFor('management-social'));
            //endregion
        }

        //region Render Page
        return $this->view->render($response, 'management/social/edit.twig', $returnData);
        //endregion
    }

    public function postEdit($request, $response)
    {
        //region Return Data
        $returnData = [
            'message' => null
        ];
        //endregion

        try {

            //region Paramater Validation
            $validation = $this->validator->validate($request, [
                'facebook' => v::stringType()->length(null,100),
                'facebook_username' => v::stringType()->length(null,50),
                'twitter' => v::stringType()->length(null,100),
                'twitter_username' => v::stringType()->length(null,50),
                'instagram' => v::stringType()->length(null,100),
                'instagram_username' => v::stringType()->length(null,50),
                'google_plus' => v::stringType()->length(null,100),
                'google_plus_username' => v::stringType()->length(null,50),
                'pinterest' => v::stringType()->length(null,100),
                'pinterest_username' => v::stringType()->length(null,50),
                'linkedin' => v::stringType()->length(null,100),
                'linkedin_username' => v::stringType()->length(null,50),
                'tumblr' => v::stringType()->length(null,100),
                'tumblr_username' => v::stringType()->length(null,50)
            ]);

            if ($validation->failed()) {
                //region Set Flash Message
                $this->flash->addMessage(Config\Flash::ERROR, Config\Message\General::FORM_FIELD_VALIDATION);
                //endregion

                return $response->withRedirect($this->router->pathFor('management-social'));
            }
            //endregion

            //region Main Variables
            $facebook = null;
            $facebook_username= null;
            $twitter= null;
            $twitter_username= null;
            $instagram= null;
            $instagram_username= null;
            $google_plus= null;
            $google_plus_username= null;
            $pinterest= null;
            $pinterest_username= null;
            $linkedin= null;
            $linkedin_username= null;
            $tumblr= null;
            $tumblr_username= null;
            //endregion

            //region Request Control
            if (null !== $request->getParam('facebook')) {
                $facebook = Functions\General::clearFormInput($request->getParam('facebook'));
            }
            if (null !== $request->getParam('facebook_username')) {
                $facebook_username = Functions\General::clearFormInput($request->getParam('facebook_username'));
            }
            if (null !== $request->getParam('twitter')) {
                $twitter = Functions\General::clearFormInput($request->getParam('twitter'));
            }
            if (null !== $request->getParam('twitter_username')) {
                $twitter_username = Functions\General::clearFormInput($request->getParam('twitter_username'));
            }
            if (null !== $request->getParam('instagram')) {
                $instagram = Functions\General::clearFormInput($request->getParam('instagram'));
            }
            if (null !== $request->getParam('instagram_username')) {
                $instagram_username = Functions\General::clearFormInput($request->getParam('instagram_username'));
            }
            if (null !== $request->getParam('google_plus')) {
                $google_plus = Functions\General::clearFormInput($request->getParam('google_plus'));
            }
            if (null !== $request->getParam('google_plus_username')) {
                $google_plus_username = Functions\General::clearFormInput($request->getParam('google_plus_username'));
            }
            if (null !== $request->getParam('pinterest')) {
                $pinterest = Functions\General::clearFormInput($request->getParam('pinterest'));
            }
            if (null !== $request->getParam('pinterest_username')) {
                $pinterest_username = Functions\General::clearFormInput($request->getParam('pinterest_username'));
            }
            if (null !== $request->getParam('linkedin')) {
                $linkedin = Functions\General::clearFormInput($request->getParam('linkedin'));
            }
            if (null !== $request->getParam('linkedin_username')) {
                $linkedin_username = Functions\General::clearFormInput($request->getParam('linkedin_username'));
            }
            if (null !== $request->getParam('tumblr')) {
                $tumblr = Functions\General::clearFormInput($request->getParam('tumblr'));
            }
            if (null !== $request->getParam('tumblr_username')) {
                $tumblr_username = Functions\General::clearFormInput($request->getParam('tumblr_username'));
            }
            //endregion

            //region Table Update
            $data = [
                'facebook' => $facebook,
                'facebook_username' => $facebook_username,
                'twitter' => $twitter,
                'twitter_username' => $twitter_username,
                'instagram' => $instagram,
                'instagram_username' => $instagram_username,
                'google_plus' => $google_plus,
                'google_plus_username' => $google_plus_username,
                'pinterest' => $pinterest,
                'pinterest_username' => $pinterest_username,
                'linkedin' => $linkedin,
                'linkedin_username' => $linkedin_username,
                'tumblr' => $tumblr,
                'tumblr_username' => $tumblr_username,
                'updated_at' => Carbon::now(),
                'updated_by' => $this->container->management->admin()->id
            ];

            $table = TblSetupSocial::where('id', 1)->update($data);
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
            return $response->withRedirect($this->router->pathFor('management-social'));
            //endregion
        }

        return $response->withRedirect($this->router->pathFor('management-social'));
    }
}