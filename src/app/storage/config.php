<?php

   return [
        'app' => [
            'baseUri'  => 'localhost',
            'env'      => 'demo',
            'name'     => 'Spotify Application',
            'url'      => 'localhost:8080',
            'version'  => '1.0.0',
            'time'     => microtime(true),
        ],
        'db' => [
            'host'     => 'localhost:5000',
            'username' => 'root',
            'password' => 'secret',
            'dbname'   => 'phalcon',
        ],
    ];
