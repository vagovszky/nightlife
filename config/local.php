<?php
return array(
    'doctrine' => array(
        'connection' => array(
            'orm_default' => array(
                'driverClass' => 'Doctrine\DBAL\Driver\PDOPgSql\Driver',
                'params' => array(
                    'host'     => 'localhost',
                    'port'     => '5432',
                    'user'     => 'nlp',
                    'password' => 'nlp',
                    'dbname'   => 'nlp',
                ),
            ),
        ),
    ),
    'mail' => array(
        'transport' => array(
            'options' => array(
                'host'              => 'smtp.gmail.com',
                'connection_class'  => 'login',
                'connection_config' => array(
                    'username' => 'cf8qde01@gmail.com',
                    'password' => '',
                    'ssl' => 'tls'
                ),
            ),  
        ),
    ),
);
