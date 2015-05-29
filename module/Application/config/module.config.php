<?php
namespace Application;

return array(
    'controllers' => array(
        'invokables' => array(
            'Application\Controller\Index' => 'Application\Controller\IndexController'
        ),
    ),
    'console' => array(
        'router' => array(
            'routes' => array(
                'parse' => array(
                    'options' => array(
                        'route' => 'parse',
                        'defaults' => array(
                            'controller' => 'Application\Controller\Index',
                            'action' => 'parse'
                        )
                    )
                ),
            )
        )
    ),
    'doctrine' => array(
        'driver' => array(
            __NAMESPACE__ . '_driver' => array(
                'class' => 'Doctrine\ORM\Mapping\Driver\AnnotationDriver',
                'cache' => 'array',
                'paths' => array(__DIR__ . '/../src/Entity')
            ),
            'orm_default' => array(
                'drivers' => array(
                    __NAMESPACE__ . '\Entity' => __NAMESPACE__ . '_driver'
                ),
            ),
        ),
    ),
    'service_manager' => array(
        'invokables' => array(
            'PHPHtmlParser\Dom' => 'PHPHtmlParser\Dom',
        ),
        'factories' => array(
            'Application\Service\Parser' => 'Application\Factory\ParserFactory',
        ),
        'abstract_factories' => array(
            'Zend\Log\LoggerAbstractServiceFactory',
        ),
    ),
    'sources' => array(
        'baseUrl' => 'http://www.the-night-life.cz',
        'listUrl' => 'http://www.the-night-life.cz/udalosti?filter[city]=Brno&count=10'
    ),
    'log' => array(
        'Application\Logger' => array(
            'writers' => array(
                array(
                    'name' => 'stream',
                    'priority' => 1000,
                    'options' => array(
                        'stream' => 'php://output',
                    ),
                ),
            ),
        ),
    ),
    'input_filters' => array(
        'invokables' => array(
            'Application\Filter\EventFilter' => 'Application\Filter\EventFilter',
         ),
    ),
);