<?php

//region Namespace
namespace App\Controllers\Management;
//endregion

//region Using
use App\Config;
use App\Functions;
use App\Models\TblSetupSystem;
use Respect\Validation\Validator as v;
use Carbon\Carbon;
//endregion

class SystemController extends Controller
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
            $table = TblSetupSystem::where('id', 1);
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
                return $response->withRedirect($this->router->pathFor('management-system'));
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
            return $response->withRedirect($this->router->pathFor('management-system'));
            //endregion
        }

        //region Render Page
        return $this->view->render($response, 'management/system/edit.twig', $returnData);
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
                'title' => v::stringType()->notEmpty()->length(null,50),
                'domain' => v::stringType()->notEmpty()->length(null,200),
                'seo_title' => v::stringType()->notEmpty()->length(null,70),
                'seo_description' => v::stringType()->notEmpty()->length(null,100),
                'seo_keyword' => v::stringType()->length(null,150),
                'meta_author' => v::stringType()->length(null,150),
                'meta_robots' => v::stringType()->length(null,150),
                'meta_application_name' => v::stringType()->length(null,150),
                'og_title' => v::stringType()->length(null,50),
                'og_description' => v::stringType()->length(null,100),
                'og_twitter_card' => v::stringType()->length(null,50),
                'og_twitter_site' => v::stringType()->length(null,50),
                'og_twitter_creator' => v::stringType()->length(null,50)
            ]);

            if ($validation->failed()) {
                //region Set Flash Message
                $this->flash->addMessage(Config\Flash::ERROR, Config\Message\General::FORM_FIELD_VALIDATION);
                //endregion

                return $response->withRedirect($this->router->pathFor('management-system'));
            }
            //endregion

            //region Main Variables
            $title = null;
            $domain = null;

            $seoTitle = null;
            $seoDescription = null;
            $seoKeyword = null;

            $metaAuthor = null;
            $metaRobots = null;
            $metaApplicationName = null;
            $metaOther = null;

            $ogTitle = null;
            $ogDescription = null;
            $ogTwitterCard = null;
            $ogTwitterSite = null;
            $ogTwitterCreator = null;

            $defaultMailId = null;
            $contactMailId = null;
            //endregion

            //region Request Control
            if (null !== $request->getParam('title')) {
                $title = Functions\General::clearFormInput($request->getParam('title'));
            }
            if (null !== $request->getParam('domain')) {
                $domain = Functions\General::clearFormInput($request->getParam('domain'));
            }

            if (null !== $request->getParam('seo_title')) {
                $seoTitle = Functions\General::clearFormInput($request->getParam('seo_title'));
            }
            if (null !== $request->getParam('seo_description')) {
                $seoDescription = Functions\General::clearFormInput($request->getParam('seo_description'));
            }
            if (null !== $request->getParam('seo_keyword')) {
                $seoKeyword = Functions\General::clearFormInput($request->getParam('seo_keyword'));
            }

            if (null !== $request->getParam('meta_author')) {
                $metaAuthor = Functions\General::clearFormInput($request->getParam('meta_author'));
            }
            if (null !== $request->getParam('meta_robots')) {
                $metaRobots = Functions\General::clearFormInput($request->getParam('meta_robots'));
            }
            if (null !== $request->getParam('meta_application_name')) {
                $metaApplicationName = Functions\General::clearFormInput($request->getParam('meta_application_name'));
            }
            if (null !== $request->getParam('meta_other')) {
                $metaOther = Functions\General::clearFormInput($request->getParam('meta_other'));
            }

            if (null !== $request->getParam('og_title')) {
                $ogTitle = Functions\General::clearFormInput($request->getParam('og_title'));
            }
            if (null !== $request->getParam('og_description')) {
                $ogDescription = Functions\General::clearFormInput($request->getParam('og_description'));
            }
            if (null !== $request->getParam('og_twitter_card')) {
                $ogTwitterCard = Functions\General::clearFormInput($request->getParam('og_twitter_card'));
            }
            if (null !== $request->getParam('og_twitter_site')) {
                $ogTwitterSite = Functions\General::clearFormInput($request->getParam('og_twitter_site'));
            }
            if (null !== $request->getParam('og_twitter_creator')) {
                $ogTwitterCreator = Functions\General::clearFormInput($request->getParam('og_twitter_creator'));
            }

            if (!empty($request->getParam('default_mail_id'))) {
                $defaultMailId = (int)$request->getParam('default_mail_id');
            }
            if (!empty($request->getParam('contact_mail_id'))) {
                $contactMailId = (int)$request->getParam('contact_mail_id');
            }
            //endregion

            //region Table Update
            $data = [
                'title' => $title,
                'domain' => $domain,
                'seo_title' => $seoTitle,
                'seo_description' => $seoDescription,
                'seo_keyword' => $seoKeyword,

                'meta_author' => $metaAuthor,
                'meta_robots' => $metaRobots,
                'meta_application_name' => $metaApplicationName,
                'meta_other' => $metaOther,

                'og_title' => $ogTitle,
                'og_description' => $ogDescription,
                'og_twitter_card' => $ogTwitterCard,
                'og_twitter_site'  => $ogTwitterSite,
                'og_twitter_creator'  => $ogTwitterCreator,

                'default_mail_id' => $defaultMailId,
                'contact_mail_id' => $contactMailId,
                'updated_at' => Carbon::now(),
                'updated_by' => $this->container->management->admin()->id
            ];

            $table = TblSetupSystem::where('id', 1)->update($data);
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
            return $response->withRedirect($this->router->pathFor('management-system'));
            //endregion
        }

        return $response->withRedirect($this->router->pathFor('management-system'));
    }
}