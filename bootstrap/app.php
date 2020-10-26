<?php

session_start();

//region Using
use Respect\Validation\Validator as v;
//endregion

//region Include Autoload
require __DIR__ . '/../vendor/autoload.php';
//endregion

// Load Environment file
$dotenv = new Dotenv\Dotenv(__DIR__, '/../environments.env');
$dotenv->load();

//region Slim Settings
$app = new \Slim\App([
    'settings'=>[
        'determineRouteBeforeAppMiddleware' => false,
        'displayErrorDetails' => true,
        'db' => [
            'driver'=>'mysql',
            'host' => getenv('DB_HOST'),
            'port' => getenv('DB_PORT'),
            'database'=> getenv('DB_DATABASE'),
            'username'=> getenv('DB_USERNAME'),
            'password'=> getenv('DB_PASSWORD'),
            'charset'=>'utf8',
            'collation'=>'utf8_general_ci',
            'prefix'=>''
        ],
        "jwt" => [
            'secret' => getenv('JWT_TOKEN')
        ]
    ]
]);
//endregion

$container = $app->getContainer();

//region Database Connection
$capsule = new \Illuminate\Database\Capsule\Manager;
$capsule->addConnection($container['settings']['db']);
$capsule->setAsGlobal();
$capsule->bootEloquent();

$container['db'] = function ($container) use ($capsule){
    return $capsule;
};
//endregion

//region Define Member
$container['member']=function ($container) {
    return new \App\Functions\Member;
};
//endregion

//region Define Management
$container['management']=function ($container) {
    return new \App\Functions\Management;
};
//endregion

//region Register Slim/Flash
$container['flash'] = function () {
    return new \Slim\Flash\Messages();
};
//endregion

//region View Define
$container['view'] = function ($container) {
    $view = new \Slim\Views\Twig(__DIR__ . '/../resources/views', [
        'cache' => false
    ]);

    $view->addExtension(new \Slim\Views\TwigExtension(
        $container->router,
        $container->request->getUri()
    ));

    $view->addExtension(new \App\Helpers\MyTwigExtensions(
        $container->router,
        $container->request->getUri()
    ));

    $view->getEnvironment()->addGlobal('member',[
        'check'=>$container->member->check(),
        'user'=>$container->member->user()
    ]);

    $view->getEnvironment()->addGlobal('management',[
        'check'=>$container->management->check(),
        'admin'=>$container->management->admin()
    ]);

    $view->getEnvironment()->addGlobal('flash', $container->flash);

    return $view;
};
//endregion

//region Namespace Define
$container['validator'] = function ($container){
    return new App\Validation\Validator;
};

//region Controller

//region Site Controller
$container['HomeController'] = function ($container){
    return new \App\Controllers\HomeController($container);
};

$container['MemberController'] = function ($container){
    return new \App\Controllers\MemberController($container);
};

$container['RegisterController'] = function ($container){
    return new \App\Controllers\RegisterController($container);
};

$container['LoginController'] = function ($container){
    return new \App\Controllers\LoginController($container);
};

$container['ProductController'] = function ($container){
    return new \App\Controllers\ProductController($container);
};

$container['ContactController'] = function ($container){
    return new \App\Controllers\ContactController($container);
};
//endregion

//region Management Controller
$container['Management\HomeController'] = function ($container){
    return new \App\Controllers\Management\HomeController($container);
};

$container['Management\LoginController'] = function ($container){
    return new \App\Controllers\Management\LoginController($container);
};

$container['Management\LogoutController'] = function ($container){
    return new \App\Controllers\Management\LogoutController($container);
};

$container['Management\ChangePasswordController'] = function ($container){
    return new \App\Controllers\Management\ChangePasswordController($container);
};

$container['Management\AdminController'] = function ($container){
    return new \App\Controllers\Management\AdminController($container);
};

$container['Management\MemberController'] = function ($container){
    return new \App\Controllers\Management\MemberController($container);
};

$container['Management\ServiceController'] = function ($container) {
    return new \App\Controllers\Management\ServiceController($container);
};

$container['Management\CountryController'] = function ($container) {
    return new \App\Controllers\Management\CountryController($container);
};

$container['Management\CityController'] = function ($container) {
    return new \App\Controllers\Management\CityController($container);
};

$container['Management\TownController'] = function ($container) {
    return new \App\Controllers\Management\TownController($container);
};

$container['Management\DistrictController'] = function ($container) {
    return new \App\Controllers\Management\DistrictController($container);
};

$container['Management\MailController'] = function ($container) {
    return new \App\Controllers\Management\MailController($container);
};

$container['Management\SystemController'] = function ($container) {
    return new \App\Controllers\Management\SystemController($container);
};

$container['Management\ExtensionController'] = function ($container) {
    return new \App\Controllers\Management\ExtensionController($container);
};

$container['Management\SocialController'] = function ($container) {
    return new \App\Controllers\Management\SocialController($container);
};

$container['Management\CompanyController'] = function ($container) {
    return new \App\Controllers\Management\CompanyController($container);
};

$container['Management\EmployeeController'] = function ($container) {
    return new \App\Controllers\Management\EmployeeController($container);
};

$container['Management\JobController'] = function ($container) {
    return new \App\Controllers\Management\JobController($container);
};

$container['Management\EmployeeJobController'] = function ($container) {
    return new \App\Controllers\Management\EmployeeJobController($container);
};
//endregion

//region Api Controller
$container['Api\CountryController'] = function ($container) {
    return new \App\Controllers\Api\CountryController($container);
};

$container['Api\CityController'] = function ($container) {
    return new \App\Controllers\Api\CityController($container);
};

$container['Api\TownController'] = function ($container) {
    return new \App\Controllers\Api\TownController($container);
};

$container['Api\DistrictController'] = function ($container) {
    return new \App\Controllers\Api\DistrictController($container);
};

$container['Api\MemberController'] = function ($container) {
    return new \App\Controllers\Api\MemberController($container);
};

$container['Api\ServiceController'] = function ($container) {
    return new \App\Controllers\Api\ServiceController($container);
};
//endregion

//endregion

//region Register Crsf
$container['csrf']=function ($container) {
    return new \Slim\Csrf\Guard;
};
//endregion

//region Register Monolog
$container['logger'] = function($c) {
    $logger = new \Monolog\Logger('my_logger');
    $file_handler = new \Monolog\Handler\StreamHandler('../logs/app.log');
    $logger->pushHandler($file_handler);
    return $logger;
};
//endregion

//endregion

//region Middleware
$app->add(new \App\Middleware\ValidationErrorsMiddleware($container));
$app->add(new \App\Middleware\OldInputMiddleware($container));
$app->add(new \App\Middleware\CsrfViewMiddleware($container));

//region Cors
$app->add(new Tuupola\Middleware\CorsMiddleware([
    "origin" => ["*"],
    "methods" => ["GET", "POST", "PUT", "DELETE"],
    "headers.allow" => [],
    "headers.expose" => [],
    "credentials" => false,
    "cache" => 0,
]));
//endregion

//region JWT
$app->add(new \Tuupola\Middleware\JwtAuthentication([
    "path" => "/api", /* or ["/api", "/admin"] */
    "ignore" => "/api/token", /* or ["/api/token", "/admin/ping"] */
    "secret" => $container['settings']['jwt']['secret'],
    "algorithm" => ["HS256"],
    "error" => function ($response, $arguments) {

        //region Return Data
        $returnData = [
            'message' => null,
            'table' => null
        ];
        //endregion

        $returnData["message"] = App\Functions\Message::setMessage(App\Config\Message\Types::ERROR, $arguments["message"]);

        return $response->withHeader("Content-Type", "application/json")->write(json_encode($returnData, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT));
    }
]));
//endregion
//endregion

//region Csrf Added in App
$app->add($container->csrf);
//endregion

//region Special Rules
v::with('App\\Validation\\Rules');
//endregion

//region Include Routes
require __DIR__ . '/../app/routes.php';
//endregion