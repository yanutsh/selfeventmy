<?php

$params = require(__DIR__ . '/params.php');

$config = [
    'id'            => 'basic',
    'language'      => 'ru-RU',
    'basePath'      => dirname(__DIR__),
    'name'          => 'SelfEvent',
    'defaultRoute'  => 'page/frontend',
    
    /**
     * @todo Добавить компрессию после завершения разработки
     */
    'bootstrap'     => [/* 'assetsAutoCompress', */ 'log'],
    'components'    => [
        'request'      => [
            // !!! insert a secret key in the following (if it is empty) - this is required by cookie validation
            'cookieValidationKey' => 'fazt65ef7vec70jgfh',
            'baseUrl' => '',
        ],
        'cache'        => [
            'class' => 'yii\caching\FileCache',
        ],
        'user'         => [
            'identityClass'   => 'app\models\User',
            'enableAutoLogin' => true,
            //'loginUrl' => '/admin/auth/login',
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
        'mailer'       => [
            'class'            => 'yii\swiftmailer\Mailer',
            'useFileTransport' => true,  // для реальной отправки - false 
            //'transport' => [
            //    'class' => 'Swift_SmtpTransport',
            //    'host' => 'mail.hosting.reg.ru',  // e.g. smtp.mandrillapp.com or smtp.gmail.com
            //    'username' => 'selfevent@toppartner.ru',
            //    'password' => 'SashAYanutsH1953',
            //    'port' => '465', // 2525 или Port 25 is a very common port too
            //    'encryption' => 'ssl', // It is often used, check your provider or mail server specs
            //],
        ],
        'log'          => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets'    => [
                [
                    'class'  => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
        'db'           => require(__DIR__ . '/db.php'),
        'urlManager'   => [
            'enablePrettyUrl' => true,
            'showScriptName'  => false,
            'enableStrictParsing' => false,
            //'suffix'          => '/',
            //'cache'           => false,
            'rules'           => [  
                'artists' => 'page/artists',
                'requests' => 'page/requests',
                'signup' => 'page/signup',
                'logout' => 'page/logout',
                'regcust' => 'page/regcust',
               
            // [
                    // 'pattern' => 'sitemap.xml',
                    // 'route'   => 'sitemap/default/index',
                    // 'suffix'  => ''
                //],
                /*  'login/<service:google|facebook|twitter|yandex|instagram|vkontakte|mailru|odnoklassniki>' => 'page/social',
                  '/'                                                                                       => 'page/main', */
                // 'admin'                       => 'page/admin',
                // 'ajax/<action>'               => 'ajax/<action>',                
                // 'admin/<controller>'          => '<controller>/index',
                // // [
                // //     'class' => 'app\components\page\PageUrl',
                // // ],
                // 'admin/<controller>/<action>' => '<controller>/<action>',
                // '<action>'                    => 'site/<action>',
            ],
        ],
        'imageresize'  => [
            'class'        => 'noam148\imageresize\ImageResize',
            //path relative web folder
            'cachePath'    => ['assets/images'],
            //use filename (seo friendly) for resized images else use a hash
            'useFilename'  => true,
            //show full url (for example in case of a API)
            'absoluteUrl'  => false,
            'imageQuality' => 80,
        ],
        'ipgeobase'    => [
            'class'      => 'himiklab\ipgeobase\IpGeoBase',
            'useLocalDB' => false,
        ],
          /*
           * @todo Отключить оба параметра после завершения разработки для включения кеширования
           */
        'assetManager' => [
              'linkAssets'      => true,
              'appendTimestamp' => true,
              // 'bundles' => [
              //   'yii\web\JqueryAsset' => [
              //       'jsOptions' => ['position' => \yii\web\View::POS_HEAD]
              //   ],
              //],
        ],
                  
        'settings'     => [
              'class'        => 'app\components\Settings\SiteSettings',
              'settingModel' => 'app\models\Settings',
        ],
      /* 'robokassa'          => [
      'class'          => '\robokassa\Merchant',
      'baseUrl'        => 'https://auth.robokassa.ru/Merchant/Index.aspx',
      'sMerchantLogin' => '',
      'sMerchantPass1' => '',
      'sMerchantPass2' => '',
      'isTest'         => !YII_ENV_PROD,
      ] */
    ],


    'modules'       => [
        'gridview' =>  [
        'class' => '\kartik\grid\Module',
        ],
        'sitemap' => [
            'class'       => 'himiklab\sitemap\Sitemap',
            'models'      => [
                // your models
                //'app\models\Article',
                'app\models\Page',
                'app\models\ProductCategory',
                'app\models\ProductFilterSeo',
                'app\models\Product',
                'app\models\News',
            ],
            'enableGzip'  => true, // default is false
            'cacheExpire' => 86400, // 1 second. Default is 24 hours
    	],
    	'admin' => [
            'class' => 'app\modules\admin\Module',
            'layout' => 'admin',
            'defaultRoute'  => 'main/index',
        ],
    ],
    
    
    'controllerMap' => [
        'elfinder' => [
            'class'            => 'mihaildev\elfinder\Controller',
            'access'           => ['@'], //глобальный доступ к фаил менеджеру @ - для авторизорованных , ? - для гостей , чтоб открыть всем ['@', '?']
            'disabledCommands' => ['netmount'], //отключение ненужных команд https://github.com/Studio-42/elFinder/wiki/Client-configuration-options#commands
            'roots'            => [
                [
                    'path' => 'uploads',
                    'name' => 'Uploads'
                ],
            ],
        ]
    ],
    
    'params'        => $params,
];

if(YII_ENV_DEV)
{
    // configuration adjustments for 'dev' environment
    $config['bootstrap'][]      = 'debug';
    $config['modules']['debug'] = [
        'class'      => 'yii\debug\Module',
        //'allowedIPs' => ['145.255.11.162'],
    ];

    $config['bootstrap'][]    = 'gii';
    $config['modules']['gii'] = [
        'class'      => 'yii\gii\Module',
        //'allowedIPs' => ['145.255.11.162'],
    ];
}

return $config;
