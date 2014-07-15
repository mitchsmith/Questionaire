<?php
require '../vendor/autoload.php';

// Database information
$db_settings = array(
    'driver' => 'mysql',
    'host' => '127.0.0.1',
    'database' => 'questionaire',
    'username' => 'thedoctor',
    'password' => 'tardis',
    'collation' => 'utf8_general_ci',
    'prefix' => ''
);

// Prepare Elloquent ORM
use Illuminate\Database\Capsule\Manager as Capsule;

$capsule = new Capsule;

$capsule->addConnection($db_settings);

$capsule->bootEloquent();

// Prepare app
$app = new \Slim\Slim(array(
    'templates.path' => '../templates',
));

// Create monolog logger and store logger in container as singleton 
$app->container->singleton('log', function () {
    $log = new \Monolog\Logger('questionaire');
    $log->pushHandler(new \Monolog\Handler\StreamHandler('../logs/app.log', \Monolog\Logger::DEBUG));
    return $log;
});

// Prepare view
$app->view(new \Slim\Views\Twig());
$app->view->parserOptions = array(
    'charset' => 'utf-8',
    'cache' => realpath('../templates/cache'),
    'auto_reload' => true,
    'strict_variables' => false,
    'autoescape' => true
);
$app->view->parserExtensions = array(new \Slim\Views\TwigExtension());

// Define routes
$app->get('/', function () use ($app) {
    // Sample log message
    $app->log->info("Questionaire '/' route");
    // Render index view
    $app->render('index.html');
});

$app->get('^screener/', function () use ($app) {
    // Sample log message
    $app->log->info("Questionaire 'screener/' route");
    // Render index view
    $app->render('screener_form.html');
});

$app->get('^dashboard/', function () use ($app) {
    // Sample log message
    $app->log->info("Questionaire 'dashboard/' route");
    // Render index view
    $app->render('dashboard.html');
});

// Run app
$app->run();
