<?php

//region Namespace
namespace App\Controllers;
//endregion

//region Using
use App\Config;
use App\Functions;
use App\Models\TblSetupCompany;
use Respect\Validation\Validator as v;
use PHPMailer\PHPMailer\PHPMailer;
//endregion

class ContactController extends Controller
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

            //region Category Control
            $table = TblSetupCompany::where('id', 1);

            if (!empty($table) && !empty($table->get()) && !$table->get()->isEmpty()) {
                //region Set Return Data
                $returnData["message"] = Functions\Message::setMessage(Config\Message\Types::SUCCESS,null);
                $returnData["table"] = $table->first();
                //endregion
            } else {
                //region With Redirect
                return $response->withRedirect($this->router->pathFor('category'));
                //endregion
            }
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
        return $this->view->render($response, 'site/contact/index.twig', $returnData);
        //endregion
    }

    public function postIndex($request, $response, $args)
    {
        //region Return Data
        $returnData = [
            'message' => null
        ];
        //endregion

        try {
            //region Variables
            //endregion

            //region Paramater Validation
            $validation = $this->validator->validate($request, [
                'name' => v::notEmpty()->length(6,50),
                'mail' => v::noWhitespace()->notEmpty()->email()->length(5, 320),
                'phone' =>v::notEmpty()->length(11,11),
                'message' => v::notEmpty()
            ]);

            if ($validation->failed()) {
                //region Set Flash Message
                $this->flash->addMessage(Config\Flash::ERROR, Config\Message\General::FORM_FIELD_VALIDATION);
                //endregion
                //region Redirect
                return $response->withRedirect($this->router->pathFor('product',['category_id' => $category_id, 'product_id' => $product_id]));
                //endregion
            }
            //endregion

            //region Main Variables
            $name = null;
            $mail = null;
            $phone = null;
            $message = null;
            //endregion

            //region Request Control
            if (null !== $request->getParam('name')) {
                $name = Functions\General::clearFormInput($request->getParam('name'));
            }
            if (null !== $request->getParam('mail')) {
                $mail = Functions\General::clearFormInput($request->getParam('mail'));
            }
            if (null !== $request->getParam('phone')) {
                $phone = Functions\General::clearFormInput($request->getParam('phone'));
            }
            if (null !== $request->getParam('message')) {
                $message = Functions\General::clearFormInput($request->getParam('message'));
            }
            //endregion

            //region Mail Send
            $phpMailer = new PHPMailer;

            $phpMailer->setLanguage('tr', '/optional/path/to/language/directory/');
            $phpMailer->Priority = 1;
            $phpMailer->ContentType = "text/html; charset='utf8'";
            $phpMailer->CharSet = 'UTF-8';

            $phpMailer->SMTPDebug = 2;
            $phpMailer->isSMTP();
            $phpMailer->Host = 'srvc170.trwww.com';
            $phpMailer->SMTPAuth = true;
            $phpMailer->Username = 'antikyum@antikyum.com';
            $phpMailer->Password = 'Antik!yum4545';
            $phpMailer->SMTPSecure = 'ssl';
            $phpMailer->Port = 465;

            $phpMailer->setFrom('antikyum@antikyum.com', 'Antikyum');
            $phpMailer->addAddress('antikyum@antikyum.com', 'Antikyum');
            $phpMailer->addReplyTo($mail, $name);

            $phpMailer->isHTML(true);

            $phpMailer->Subject = 'İletişim Formu';
            $phpMailer->Body = '<p>Sayın Yetkili,</p><br><p><strong>İsim:</strong> '.$name.'</p><p><strong>Telefon:</strong> <a href="tel:+9'.$phone.'">'.$phone.'</a></p><p><strong>E-posta:</strong> '.$mail.'</p><br><p><strong>Mesajı:</strong> '.$message.'</p>';

            if(!$phpMailer->send()) {
                //region Set Flash Message
                $this->flash->addMessage(Config\Flash::ERROR, $mail->errorMessage());
                //endregion

                return $response->withRedirect($this->router->pathFor('contact'));
            }
            //endregion

            //region Set Flash Message
            $this->flash->addMessage(Config\Flash::INFO, Config\Message\General::MAIL_SEND_SUCCESS);
            //endregion

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
            return $response->withRedirect($this->router->pathFor('home'));
            //endregion
        }

        return $response->withRedirect($this->router->pathFor('contact'));
    }
}