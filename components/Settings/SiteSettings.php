<?php

namespace app\components\Settings;

/**
 * config/web:
 * 
 *  'settings'     => [
 *           'class'        => 'app\components\cms\Settings\SiteSettings',
 *           'settingModel' => 'app\models\Settings',
 *       ],
 * 
 * use Yii::$app->settings->get('property')
 */
class SiteSettings
{

    public $settingModel;
    protected $settings;

    public function __construct()
    {
        $this->load();
    }

    public function __get($name)
    {
        if( $this->settings || $this->load() )
        {
            return isset($this->settings[$name]) ? $this->settings[$name] : false;
        }
        return false;
    }

    public function get($name)
    {
        if( $this->settings || $this->load() )
        {
            return isset($this->settings[$name]) ? $this->settings[$name] : false;
        }
        return false;
    }

    protected function load()
    {
        if( $this->settingModel )
        {
            $cname          = $this->settingModel;
            $this->settings = $cname::find()->where([ 'id' => 1 ])->asArray()->one();
            return $this->settings;
        }
        return false;
    }

}
