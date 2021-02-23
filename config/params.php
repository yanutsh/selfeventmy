<?php

return [
    //'adminEmail'      => 'mail@v-stepanov.ru',
    'adminEmail'      => 'selfevent@toppartner.ru',
    'serverEmail'     => 'noreply@se.k-store.ru'/* . $_SERVER['HOST_NAME'] /* . str_replace('www.', '', \Yii::$app->request->getHostInfo()) */,
    'upload_dir'      => '@webroot/uploads/',
    'image_dir'       => '@webroot/uploads/images/',
    'image_dir_url'   => '/uploads/images/',
    'download_dir'    => '@webroot/downloads/',
    'download_url'    => '/downloads/',
    'cms'             => 'CMS v0.7.2',
    'homePageId'      => 1,
    'sitemapPageId'   => 22,
    'blogPageId'      => 58,
    'portfolioPageId' => 34,
    'catalogPageId'   => 26,
    'servicePageId'   => 25,
    'industryPageId'  => 29,
    'reviewPageId'    => 59,
    'user.passwordMinLength' => 3,
    
    // сервис отправки смс с кодом подтверждения
    'login_sms'       => '79771512915',
    'password_sms'    => 'CKvihRjRHN',
    'title_sms'       => 'Код подтверждения', 
    'sadr_sms'        => 'MrSelfevent',

    // сервис отправки email с кодом подтверждения
    'email_subject'   => 'MrSelfevent-Код подтверждения',
    'timer' =>  30,     // таймер повторной отправки кода подтверждения, сек

    // Фильтр поиска заказов - Дата от         
    'date_from' => date('d.m.Y', mktime(0, 0, 0, date("m"), date("d")-30, date("Y"))), 
    'date_to' => date('d.m.Y'),  // Дата до
];
