<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace app\components\page;

use yii\helpers\Html;

/**
 * Description of PageAttributeWidget
 *
 * @author valentin
 */
class PageAttributeWidget extends \yii\base\Widget
{

    public $attributeId;
    public $pageId;
    public $attributeData;

    public function init()
    {
        parent::init();
        $this->attributeData = \app\models\PageAttribute::find()->with('siteAttribute', 'siteAttributeValue')->where(['id' => $this->attributeId])->one();
    }

    public function run()
    {
        parent::run();
        switch($this->attributeData->siteAttribute->type)
        {
            case 1:
                echo nl2br($this->attributeData->value);
                break;
            case 2:
                echo $this->attributeData->value;
                break;
            case 3:
                echo $this->attributeData->value;
                break;
        }
    }

}
