<?php

use CViniciusSDias\WebSockets\Controller\SampleController;
use Ratchet\App;
use React\EventLoop\Factory;
use React\Socket\Server;
use React\Socket\ConnectionInterface as ReactConnection;

require_once 'vendor/autoload.php';

$loop = Factory::create();

$socketServer = new Server('unix:///tmp/a.sock', $loop);

$controller = new SampleController();

$socketServer
    ->on(
        'connection',
        fn (ReactConnection $connection) => $connection->on('data', fn ($data) => $controller->broadCast($data))
    );

$app = new App(gethostbyname(gethostname()), 8080, '0.0.0.0', $loop);
$app->route('/teste', $controller, ['*']);
$app->run();
