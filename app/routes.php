<?php

//region Using
use App\Middleware\GuestMiddleware;
use App\Middleware\MemberMiddleware;
use App\Middleware\ManagementGuestMiddleware;
use App\Middleware\ManagementMiddleware;
use Firebase\JWT\JWT;
//endregion

$app->get('/','HomeController:getIndex')->setName('home');

//region Member
$app->group('', function (){
    $this->get('/kayit-ol','RegisterController:getIndex')->setName('register');
    $this->post('/kayit-ol','RegisterController:postIndex');

    $this->get('/giris','LoginController:getIndex')->setName('login');
    $this->post('/giris','LoginController:postIndex');
})->add(new GuestMiddleware($container));

$app->group('', function (){
    $this->get('/sifre-degistir','MemberController:getChangePassword')->setName('member-changepassword');
    $this->post('/sifre-degistir','MemberController:postChangePassword');

    $this->get('/cikis','MemberController:getLogout')->setName('logout');
})->add(new MemberMiddleware($container));
//endregion

//region Management Panel
$app->group('/management', function (){
    //region Management
    $this->get('/login','Management\LoginController:getIndex')->setName('management-login');
    $this->post('/login','Management\LoginController:postIndex');
    //endregion
})->add(new ManagementGuestMiddleware($container));

$app->group('/management', function (){
    $this->get('','Management\HomeController:getIndex')->setName('management');

    //region Admins
    //region Home
    $this->get('/admin','Management\AdminController:getIndex')->setName('management-admin');
    //endregion
    //region New
    $this->get('/admin/new','Management\AdminController:getNew')->setName('management-admin-new');
    $this->post('/admin/new','Management\AdminController:postNew');
    //endregion
    //region Delete
    $this->get('/admin/delete/{id:[0-9]+}','Management\AdminController:getDelete')->setName('management-admin-delete');
    $this->post('/admin/all-delete','Management\AdminController:postAllDelete')->setName('management-admin-all-delete');
    //endregion
    //region Edit
    $this->get('/admin/edit/{id:[0-9]+}','Management\AdminController:getEdit')->setName('management-admin-edit');
    $this->post('/admin/edit/{id:[0-9]+}','Management\AdminController:postEdit');
    //endregion
    //endregion

    //region Members
    //region Home
    $this->get('/member','Management\MemberController:getIndex')->setName('management-member');
    //endregion
    //region New
    $this->get('/member/new','Management\MemberController:getNew')->setName('management-member-new');
    $this->post('/member/new','Management\MemberController:postNew');
    //endregion
    //region Delete
    $this->get('/member/delete/{id:[0-9]+}','Management\MemberController:getDelete')->setName('management-member-delete');
    $this->post('/member/all-delete','Management\MemberController:postAllDelete')->setName('management-member-all-delete');
    //endregion
    //region Edit
    $this->get('/member/edit/{id:[0-9]+}','Management\MemberController:getEdit')->setName('management-member-edit');
    $this->post('/member/edit/{id:[0-9]+}','Management\MemberController:postEdit');
    //endregion
    //endregion

    //region Change Password
    $this->get('/change-password','Management\ChangePasswordController:getIndex')->setName('management-changepassword');
    $this->post('/change-password','Management\ChangePasswordController:postIndex');
    //endregion

    //region Logout
    $this->get('/logout','Management\LogoutController:getIndex')->setName('management-logout');
    //endregion

    //region Service
    //region Home
    $this->get('/service','Management\ServiceController:getIndex')->setName('management-service');
    //endregion
    //region New
    $this->get('/service/new','Management\ServiceController:getNew')->setName('management-service-new');
    $this->post('/service/new','Management\ServiceController:postNew');
    //endregion
    //region Delete
    $this->get('/service/delete/{id:[0-9]+}','Management\ServiceController:getDelete')->setName('management-service-delete');
    $this->post('/service/all-delete','Management\ServiceController:postAllDelete')->setName('management-service-all-delete');
    //endregion
    //region Edit
    $this->get('/service/edit/{id:[0-9]+}','Management\ServiceController:getEdit')->setName('management-service-edit');
    $this->post('/service/edit/{id:[0-9]+}','Management\ServiceController:postEdit');
    //endregion
    //region Field
    $this->get('/service/field/{id:[0-9]+}','Management\ServiceController:getFieldEdit')->setName('management-service-field');
    $this->post('/service/field/{id:[0-9]+}','Management\ServiceController:postFieldEdit');
    //endregion
    //endregion

    //region Country
    //region Home
    $this->get('/country','Management\CountryController:getIndex')->setName('management-country');
    //endregion
    //region New
    $this->get('/country/new','Management\CountryController:getNew')->setName('management-country-new');
    $this->post('/country/new','Management\CountryController:postNew');
    //endregion
    //region Delete
    $this->get('/country/delete/{id:[0-9]+}','Management\CountryController:getDelete')->setName('management-country-delete');
    $this->post('/country/all-delete','Management\CountryController:postAllDelete')->setName('management-country-all-delete');

    //endregion
    //region Edit
    $this->get('/country/edit/{id:[0-9]+}','Management\CountryController:getEdit')->setName('management-country-edit');
    $this->post('/country/edit/{id:[0-9]+}','Management\CountryController:postEdit');
    //endregion
    //endregion

    //region City
    //region Home
    $this->get('/city','Management\CityController:getIndex')->setName('management-city');
    //endregion
    //region New
    $this->get('/city/new','Management\CityController:getNew')->setName('management-city-new');
    $this->post('/city/new','Management\CityController:postNew');
    //endregion
    //region Delete
    $this->get('/city/delete/{id:[0-9]+}','Management\CityController:getDelete')->setName('management-city-delete');
    $this->post('/city/all-delete','Management\CityController:postAllDelete')->setName('management-city-all-delete');
    //endregion
    //region Edit
    $this->get('/city/edit/{id:[0-9]+}','Management\CityController:getEdit')->setName('management-city-edit');
    $this->post('/city/edit/{id:[0-9]+}','Management\CityController:postEdit');
    //endregion
    //endregion

    //region Town
    //region Home
    $this->get('/town','Management\TownController:getIndex')->setName('management-town');
    //endregion
    //region New
    $this->get('/town/new','Management\TownController:getNew')->setName('management-town-new');
    $this->post('/town/new','Management\TownController:postNew');
    //endregion
    //region Delete
    $this->get('/town/delete/{id:[0-9]+}','Management\TownController:getDelete')->setName('management-town-delete');
    $this->post('/town/all-delete','Management\TownController:postAllDelete')->setName('management-town-all-delete');
    //endregion
    //region Edit
    $this->get('/town/edit/{id:[0-9]+}','Management\TownController:getEdit')->setName('management-town-edit');
    $this->post('/town/edit/{id:[0-9]+}','Management\TownController:postEdit');
    //endregion
    //endregion

    //region District
    //region Home
    $this->get('/district','Management\DistrictController:getIndex')->setName('management-district');
    //endregion
    //region New
    $this->get('/district/new','Management\DistrictController:getNew')->setName('management-district-new');
    $this->post('/district/new','Management\DistrictController:postNew');
    //endregion
    //region Delete
    $this->get('/district/delete/{id:[0-9]+}','Management\DistrictController:getDelete')->setName('management-district-delete');
    $this->post('/district/all-delete','Management\DistrictController:postAllDelete')->setName('management-district-all-delete');
    //endregion
    //region Edit
    $this->get('/district/edit/{id:[0-9]+}','Management\DistrictController:getEdit')->setName('management-district-edit');
    $this->post('/district/edit/{id:[0-9]+}','Management\DistrictController:postEdit');
    //endregion
    //endregion

    //region Mail
    //region Home
    $this->get('/mail','Management\MailController:getIndex')->setName('management-mail');
    //endregion
    //region New
    $this->get('/mail/new','Management\MailController:getNew')->setName('management-mail-new');
    $this->post('/mail/new','Management\MailController:postNew');
    //endregion
    //region Delete
    $this->get('/mail/delete/{id:[0-9]+}','Management\MailController:getDelete')->setName('management-mail-delete');
    $this->post('/mail/all-delete','Management\MailController:postAllDelete')->setName('management-mail-all-delete');

    //endregion
    //region Edit
    $this->get('/mail/edit/{id:[0-9]+}','Management\MailController:getEdit')->setName('management-mail-edit');
    $this->post('/mail/edit/{id:[0-9]+}','Management\MailController:postEdit');
    //endregion
    //endregion

    //region System
    //region Edit
    $this->get('/system','Management\SystemController:getEdit')->setName('management-system');
    $this->post('/system','Management\SystemController:postEdit');
    //endregion
    //endregion

    //region Extension
    //region Edit
    $this->get('/extension','Management\ExtensionController:getEdit')->setName('management-extension');
    $this->post('/extension','Management\ExtensionController:postEdit');
    //endregion
    //endregion

    //region Social
    //region Edit
    $this->get('/social','Management\SocialController:getEdit')->setName('management-social');
    $this->post('/social','Management\SocialController:postEdit');
    //endregion
    //endregion

    //region Company
    //region Home
    $this->get('/company','Management\CompanyController:getIndex')->setName('management-company');
    //endregion
    //region New
    $this->get('/company/new','Management\CompanyController:getNew')->setName('management-company-new');
    $this->post('/company/new','Management\CompanyController:postNew');
    //endregion
    //region Delete
    $this->get('/company/delete/{id:[0-9]+}','Management\CompanyController:getDelete')->setName('management-company-delete');
    $this->post('/company/all-delete','Management\CompanyController:postAllDelete')->setName('management-company-all-delete');
    //endregion
    //region Edit
    $this->get('/company/edit/{id:[0-9]+}','Management\CompanyController:getEdit')->setName('management-company-edit');
    $this->post('/company/edit/{id:[0-9]+}','Management\CompanyController:postEdit');
    //endregion
    //endregion

    //region Employee
    //region Home
    $this->get('/employee','Management\EmployeeController:getIndex')->setName('management-employee');
    //endregion
    //region New
    $this->get('/employee/new','Management\EmployeeController:getNew')->setName('management-employee-new');
    $this->post('/employee/new','Management\EmployeeController:postNew');
    //endregion
    //region Delete
    $this->get('/employee/delete/{id:[0-9]+}','Management\EmployeeController:getDelete')->setName('management-employee-delete');
    $this->post('/employee/all-delete','Management\EmployeeController:postAllDelete')->setName('management-employee-all-delete');
    //endregion
    //region Edit
    $this->get('/employee/edit/{id:[0-9]+}','Management\EmployeeController:getEdit')->setName('management-employee-edit');
    $this->post('/employee/edit/{id:[0-9]+}','Management\EmployeeController:postEdit');
    //endregion
    //endregion

    //region Job
    //region Home
    $this->get('/job','Management\JobController:getIndex')->setName('management-job');
    //endregion
    //region New
    $this->get('/job/new','Management\JobController:getNew')->setName('management-job-new');
    $this->post('/job/new','Management\JobController:postNew');
    //endregion
    //region Delete
    $this->get('/job/delete/{id:[0-9]+}','Management\JobController:getDelete')->setName('management-job-delete');
    $this->post('/job/all-delete','Management\JobController:postAllDelete')->setName('management-job-all-delete');
    //endregion
    //region Edit
    $this->get('/job/edit/{id:[0-9]+}','Management\JobController:getEdit')->setName('management-job-edit');
    $this->post('/job/edit/{id:[0-9]+}','Management\JobController:postEdit');
    //endregion
    //endregion

    //region Employee Job
    //region Home
    $this->get('/employee-job/{id:[0-9]+}','Management\EmployeeJobController:getIndex')->setName('management-employee-job');
    //endregion
    //region New
    $this->get('/employee-job/new/{id:[0-9]+}','Management\EmployeeJobController:getNew')->setName('management-employee-job-new');
    $this->post('/employee-job/new/{id:[0-9]+}','Management\EmployeeJobController:postNew');
    //endregion
    //region Delete
    $this->get('/employee-job/delete/{id:[0-9]+}/{jobId:[0-9]+}','Management\EmployeeJobController:getDelete')->setName('management-employee-job-delete');
    $this->post('/employee-job/all-delete/{id:[0-9]+}','Management\EmployeeJobController:postAllDelete')->setName('management-employee-job-all-delete');
    //endregion
    //endregion
})->add(new ManagementMiddleware($container));
//endregion

//region Api
$app->group('/api', function (){

    //region Token
    $this->get('/token', function ($request, $response, $args) {
        //region Return Data
        $returnData = [
            'message' => null,
            'table' => null
        ];
        //endregion

        try {

            //region Variables
            //region Table Variables
            $table[] = null;
            //endregion
            //endregion

            //region Method Control
            if ($_SERVER['REQUEST_METHOD'] == 'GET') {

                $settings = $this->get('settings');

                $table = [
                    'token' => JWT::encode(['ip' => App\Functions\Network::getIp()], $settings['jwt']['secret'], "HS256")
                ];

                //region Set Return Data
                $returnData["message"] = App\Functions\Message::setMessage(App\Config\Message\Types::SUCCESS,null);
                $returnData["table"] = $table;
                //endregion

            } else {
                $returnData["message"] = App\Functions\Message::setMessage(App\Config\Message\Types::ERROR,App\Config\Message\General::FORM_METHOD_ERROR);
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
    });
    //endregion

    //region Country
    //region List
    $this->get('/countries','Api\CountryController:getList');
    //endregion
    //region Row
    $this->get('/country/{id:[0-9]+}','Api\CountryController:getRow');
    //endregion
    //endregion

    //region City
    //region List
    $this->get('/cities/{id:[0-9]+}','Api\CityController:getList');
    //endregion
    //region Row
    $this->get('/city/{id:[0-9]+}','Api\CityController:getRow');
    //endregion
    //endregion

    //region Town
    //region List
    $this->get('/towns/{id:[0-9]+}','Api\TownController:getList');
    //endregion
    //region Row
    $this->get('/town/{id:[0-9]+}','Api\TownController:getRow');
    //endregion
    //endregion

    //region District
    //region List
    $this->get('/districts/{id:[0-9]+}','Api\DistrictController:getList');
    //endregion
    //region Row
    $this->get('/district/{id:[0-9]+}','Api\DistrictController:getRow');
    //endregion
    //endregion

    //region Member
    //region Login
    $this->get('/member/login','Api\MemberController:getLogin');
    //endregion

    //region Register
    $this->post('/member/register','Api\MemberController:postRegister');
    //endregion
    //endregion

    //region Service
    //region List
    $this->get('/service','Api\ServiceController:getList');
    //endregion
    //region Row
    $this->get('/service/{id:[0-9]+}','Api\ServiceController:getRow');
    //endregion
    //region Field
    $this->get('/service/field/{id:[0-9]+}','Api\ServiceController:getField');
    //endregion
    //endregion
});
//endregion

//region Contact
$app->get('/iletisim','ContactController:getIndex')->setName('contact');
$app->post('/iletisim','ContactController:postIndex');
//endregion

//region Product
$app->get('/{category_id}/{product_id}','ProductController:getIndex')->setName('product');
$app->post('/{category_id}/{product_id}','ProductController:postIndex');
//endregion