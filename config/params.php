<?php
use app\models\Category;

$cache = \Yii::$app->cache;
//$category = $cache->getOrSet('category', function(){
//             return Category::find()->orderBy('name')->asArray()->all();});

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
    'timer' =>  60,     // таймер повторной отправки кода подтверждения, сек

    // Фильтр поиска заказов - Дата от         
    'date_from' => date('d.m.Y', mktime(0, 0, 0, date("m"), date("d")-365, date("Y"))), 
    'date_to' => date('d.m.Y', mktime(0, 0, 0, date("m"), date("d")+1, date("Y"))),    // Дата до

    // Предустановленные даты мероприятия         
    'event_date_from' => date('d.m.Y', mktime(0, 0, 0, date("m"), date("d")+1, date("Y"))), 
    'event_date_to' => date('d.m.Y', mktime(0, 0, 0, date("m"), date("d")+1, date("Y"))), 

    'docs_check_1' => 'Пройдите верификацию для подтверждения данных в профиле и получения статуса "проверенный профиль"',
    'docs_check_2' => 'Проверка не займет более 5 минут.', 
    'docs_checking_1' => 'Данные сейчас проверяются. пожалуйста ожидайте, процедура может занять от 3-х минут до 3-х часов.',
    'docs_checking_2' => 'Ожидайте подтверждения.',
    'docs_error' => 'Документ удостоверения личности был проверен, но данные отличаются. Пожалуйста исправьте данные в профиле на верные.',
    
    
];
