<?php

use Phalcon\Di\FactoryDefault;
use Phalcon\Loader;
use Phalcon\Mvc\View;
use Phalcon\Mvc\Application;
use Phalcon\Url;
use Phalcon\Config;
use Phalcon\Config\ConfigFactory;
use Phalcon\Mvc\Router;
use Phalcon\Db\Adapter\Pdo\Mysql;
use Phalcon\Session\Manager;
use Phalcon\Session\Adapter\Stream;
use Phalcon\Http\Cookie;
use Phalcon\Logger\AdapterFactory;
use Phalcon\Logger\LoggerFactory;

// Define some absolute path constants to aid in locating resources
define('BASE_PATH', dirname(__DIR__));
define('APP_PATH', BASE_PATH . '/app');

// Register an autoloader
$loader = new Loader();

$loader->registerDirs(
    [
        APP_PATH . '/controllers/',
        APP_PATH . '/models/',
        APP_PATH . '/components/',
    ]
);

// Register some classes
$loader->registerFiles(
    [
        '../app/components/myescaper.php',
    ]
);

$loader->register();

$container = new FactoryDefault();

$container->set(
    'view',
    function () {
        $view = new View();
        $view->setViewsDir(APP_PATH . '/views/');
        return $view;
    }
);

$container->set(
    'url',
    function () {
        $url = new Url();
        $url->setBaseUri('/');
        return $url;
    }
);

$container->set(
    'config',
    function () {
        $fileName = '../app/storage/config.php';
        $factory  = new ConfigFactory();
        $config   = $factory->newInstance('php', $fileName);
        return $config;
    }
);

$container->set(
    'db',
    function () {
        return new Mysql(
            [
                'host'     => 'mysql-server',
                'username' => 'root',
                'password' => 'secret',
                'dbname'   => 'phalcon',
            ]
        );
    }
);

// Start the session the first time when some component request the session service
$container->set(
    'session',
    function () {
        $session = new Manager();
        $files = new Stream(
            [
                'savePath' => '/tmp',
            ]
        );
        $session->setAdapter($files);
        $session->start();

        return $session;
    },
    true
);

$container->set(
    'cookie',
    function () {
        $auth = $_COOKIE['remember_me'] ?? false;
        $cookie  = new Cookie(
            'user-auth',                   // name
            $auth,                       // value
            time() + 86400,                // expires
            "/",                           // path
            true,                          // secure
            ".phalcon.io",                 // domain
            true,                          // httponly
            [                              // options
                "samesite" => "Strict",    // 
            ]                              // 
        );
        return $cookie;
    },
    true
);

$container->set(
    'timestamp',
    function () {
        return date("Y/m/d");
    },
    true
);

$container->set(
    'signup-logger',
    function () {
        $config = [
            "name"     => "prod-signup-logger",
            "adapters" => [
                "signup"  => [
                    "adapter" => "stream",
                    "name"    => "../app/logs/signup.log",
                    "options" => []
                ],
            ],
        ];
        $adapterFactory = new AdapterFactory();
        $loggerFactory  = new LoggerFactory($adapterFactory);
        return $loggerFactory->load($config);
    },
    true
);

$container->set(
    'login-logger',
    function () {
        $config = [
            "name"     => "prod-login-logger",
            "adapters" => [
                "login" => [
                    "adapter" => "stream",
                    "name"    => "../app/logs/login.log",
                    "options" => []
                ],
            ],
        ];
        $adapterFactory = new AdapterFactory();
        $loggerFactory  = new LoggerFactory($adapterFactory);
        return $loggerFactory->load($config);
    },
    true
);

$application = new Application( $container );

// Register autoloader.
$loader->register();

try {
    // Handle the request.
    $response = $application->handle(
        $_SERVER["REQUEST_URI"]
    );

    $response->send();
} catch (\Exception $e) {
    echo 'Exception: ', $e->getMessage();
}