<?php

//region Namespace
namespace App\Controllers\Management;
//endregion

//region Using
use App\Config;
use App\Functions;
use App\Models\TblSetupExtension;
use Respect\Validation\Validator as v;
use Carbon\Carbon;
//endregion

class ExtensionController extends Controller
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
            $table = TblSetupExtension::where('id', 1);
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
                return $response->withRedirect($this->router->pathFor('management-extension'));
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
            return $response->withRedirect($this->router->pathFor('management-extension'));
            //endregion
        }

        //region Render Page
        return $this->view->render($response, 'management/extension/edit.twig', $returnData);
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
            ]);

            if ($validation->failed()) {
                //region Set Flash Message
                $this->flash->addMessage(Config\Flash::ERROR, Config\Message\General::FORM_FIELD_VALIDATION);
                //endregion

                return $response->withRedirect($this->router->pathFor('management-extension'));
            }
            //endregion

            //region Main Variables
            $googleAnalytics = null;
            $googleAnalyticsId = null;
            $yandexMetrica = null;
            $yandexMetricaId = null;
            $googleSiteVerification = null;
            $bingSiteVerification = null;
            $supportChat = null;
            //endregion

            //region Request Control
            if (null !== $request->getParam('google_analytics')) {
                $googleAnalytics = Functions\General::clearFormInput($request->getParam('google_analytics'));
            }
            if (null !== $request->getParam('google_analytics_id')) {
                $googleAnalyticsId = Functions\General::clearFormInput($request->getParam('google_analytics_id'));
            }
            if (null !== $request->getParam('yandex_metrica')) {
                $yandexMetrica = Functions\General::clearFormInput($request->getParam('yandex_metrica'));
            }
            if (null !== $request->getParam('yandex_metrica_id')) {
                $yandexMetricaId = Functions\General::clearFormInput($request->getParam('yandex_metrica_id'));
            }
            if (null !== $request->getParam('google_site_verification')) {
                $googleSiteVerification = Functions\General::clearFormInput($request->getParam('google_site_verification'));
            }
            if (null !== $request->getParam('bing_site_verification')) {
                $bingSiteVerification = Functions\General::clearFormInput($request->getParam('bing_site_verification'));
            }
            if (null !== $request->getParam('support_chat')) {
                $supportChat = Functions\General::clearFormInput($request->getParam('support_chat'));
            }
            //endregion

            //region Table Update
            $data = [
                'google_analytics' => $googleAnalytics,
                'google_analytics_id' => $googleAnalyticsId,
                'yandex_metrica' => $yandexMetrica,
                'yandex_metrica_id' => $yandexMetricaId,
                'google_site_verification' => $googleSiteVerification,
                'bing_site_verification' => $bingSiteVerification,
                'support_chat' => $supportChat,
                'updated_at' => Carbon::now(),
                'updated_by' => $this->container->management->admin()->id
            ];

            $table = TblSetupExtension::where('id', 1)->update($data);
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
            return $response->withRedirect($this->router->pathFor('management-extension'));
            //endregion
        }

        return $response->withRedirect($this->router->pathFor('management-extension'));
    }
}