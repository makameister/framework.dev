<?php declare(strict_types = 1);

use App\Blog\BlogModule;
use Framework\App;
use App\Home\HomeModule;
use Framework\Http\HttpRequest;
use Framework\Middleware\DispatcherMiddleware;
use Framework\Middleware\NotFoundMiddleware;
use Framework\Middleware\RouterMiddleware;
use Framework\Middleware\TrailingSlashMiddleware;
use function Http\Response\send;

chdir(dirname(__DIR__));

require 'vendor/autoload.php';

putenv('ENV=dev');
ini_set('display_errors', 'true');
error_reporting(E_ALL);

$app = new App('config/config.php');

$app
    ->addModule(HomeModule::class)
    ->addModule(BlogModule::class)
    ->pipe(TrailingSlashMiddleware::class)
    ->pipe(RouterMiddleware::class)
    ->pipe(DispatcherMiddleware::class)
    ->pipe(NotFoundMiddleware::class)
    ;

if (php_sapi_name() !== 'cli') {
    $response = $app->run(HttpRequest::fromGlobals());
    send($response);
}
