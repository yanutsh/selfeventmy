<?php 
    use app\components\city\assets\CityAsset;
    use yii\helpers\Html;
    use yii\helpers\Url;
    use yii\jui\AutoComplete;
    use yii\web\JsExpression;


    CityAsset::register($this);
        
        \yii\widgets\Pjax::begin([
            'id'              => 'user-city',
            'enablePushState' => false,
            'options'         => [
                'class' => 'city-widget',
            ],
        ]);

        // значение города из виджета - из кукис или по умолчанию
        $this->context->cityName = $cityName;
        
        // если существует - вставляем значение города из PageController
        if ($this->params['inputCity']) $this->context->cityName = $this->params['inputCity'];
        
        echo Html::a(
            Html::tag('span', 
                Html::img(\Yii::$app->params['image_dir_url'] . 'map.png'), ['class' => 'image']) . Html::tag('span', $this->context->cityName, ['class' => 'name']), 
                    '#popup-city', ['class' => 'popup-inline', 'data-mfp-src' => '#popup-city']);        
        \yii\widgets\Pjax::end();

        // pop-up окно
        echo Html::beginTag('div', ['class' => 'mfp-hide popup-form popup-city', 'id' => 'popup-city']);       
            
            echo Html::tag('div', 'Выберите ваш город', ['class' => 'popup-header']);

                $regions = \app\models\AllowedCity::find()->select(['region', 'region_name'])->distinct()->asArray()->all();
                $regions = array_merge([['region' => 0, 'region_name' => 'Выберите регион']], $regions);  

                // \yii\widgets\Pjax::begin([
                //     'id'                 => 'city-from-region',
                //     'formSelector'       => '#region-select-form',
                //     'enablePushState'    => false,
                //     'enableReplaceState' => false,
                //     // 'enablePushState'    => true,
                //     // 'enableReplaceState' => true,
                // ]);

                     
                // форма выбора региона
                //echo Html::beginForm('/', 'get', ['id' => 'region-select-form']);
                echo Html::beginForm('/', 'get', ['id' => 'region-select-form', 'data'=>['pjax'=>false]]);

                    //echo ("params-inputCity=".$this->params['inputCity']);

                    $cities  = \app\models\AllowedCity::find()->orderBy(['city_name' => SORT_ASC])->asArray()->all();

                    $cities  = array_merge([['id' => 0, 'city' => 0, 'city_name' => 'Выберите город']], $cities);
                    
                   //$values  = [];

                    // foreach($cities as $c)
                    // {
                    //     $values[] = $c['city_name'];
                    // }

                   // debug($values);

                    // оздаем автозаполняемое поле ввода города
                        echo Html::beginTag('div', ['class' => 'input-group']);        

                            // echo \yii\jui\AutoComplete::widget([
                            //     'clientOptions' => [
                            //         'source'   => $values,
                            //         //'focus'    => new \yii\web\JsExpression('function(event, ui){ ui.autocomplete("enable"); }'),
                            //         // 'select' => new JsExpression("function( event, ui ) {
                            //         // $('#inputCity').val(ui.item.id);}"),
                            //         'autoFill' => true,
                            //         'appendTo' => '#popup-city',
                            //     ],
                            //     'name'          => 'inputCity',
                            //     'options'       => [
                            //         'class'       => 'form-control input-city',
                            //         'placeholder' => 'Найти город...',               
                            //     ],
                            //     // 'value'=>'Сталинград',
                            //     'id'            => 'ac' . \Yii::$app->security->generateRandomString(3),
                            // ]);

                            // echo Html::dropDownList('inputCity', $values, \yii\helpers\ArrayHelper::map($cities, 'id', 'city_name'), ['onchange' => new \yii\web\JsExpression("$('#region-select-form').submit()")]);

                            echo Html::dropDownList('inputCity', isset(\Yii::$app->request->get()['inputCity']) ? \Yii::$app->request->get()['inputCity'] : 0, \yii\helpers\ArrayHelper::map($cities, 'city_name', 'city_name'), ['onchange' => new \yii\web\JsExpression("$('#region-select-form').submit();")]);

                            
                        echo Html::endTag('div');
                echo Html::endForm();

                                
                $creg = '';
                $ul   = [];
                foreach($cities as $city)
                    {
                        if($city['id'])
                            $ul[] = Html::a($city['city_name'],
                                     '/?inputCity=' . $city['city_name'] 
                                 );
                            
                    }              
                          
                echo Html::ul($ul, ['encode' => false]);
                                
            echo Html::endTag('div');
                      
        //\yii\widgets\Pjax::end();