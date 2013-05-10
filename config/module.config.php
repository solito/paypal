<?php
return array(
    'service_manager' => array(
        'factories' => array(
            'Zend\Log\Logger' => 'Zend\Log\LoggerServiceFactory',
            'PayPal\Service\IPN' => 'PayPal\Service\IPNServiceFactory',
            'PayPal\Service\ButtonService' => 'PayPal\Service\ButtonServiceFactory',
        ),
    ),
    
    'log' => array(
        'writers' => array(
            'db' => array(
                'name'     => 'Zend\Log\Writer\Null',
                'filters' => array(
                )
            )
        ),
    ),
    
    'router' => array(
        'routes' => array(
            'ipn' => array(
                'type' => 'Literal',
                'priority' => 1000,
                'options' => array(
                    'route' => '/ipn',
                    'defaults' => array(
                        'controller' => 'ipn',
                    ),
                ),
                'may_terminate' => true,
            ),
         ),
    ),
    'controllers' => array(
        'invokables' => array(
            'ipn'       => 'PayPal\Controller\IpnController',
        ),
    ),
    
   'paypal' => array(
      //Account credentials
       'business'     => 'somebusiness',
       'environment'  => '',
       'notifyUrl'    => '', 
       'cacert'       => '',
    )
);
