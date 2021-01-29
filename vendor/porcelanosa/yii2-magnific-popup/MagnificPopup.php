<?php

namespace porcelanosa\magnificPopup;

use yii;
use yii\web\JsExpression;
use yii\helpers\Json;

class MagnificPopup extends \yii\base\Widget
{
    /**
     * Jquery selector to which element should apply the magnific-popup.
     * @var string jQuery Selector
     */
    public $target;
    
    /**
     * Options in the documentation for magnific-popup
     * @see http://dimsemenov.com/plugins/magnific-popup/documentation.html
     * @var array Magnific-Popup Option
     */
    public $options = array();
    
    /**
     * @var type 
     */
    public $defaultOptions = array(
        'type' => 'image'
    );
    /**
     * Language for internationalization.
     * Null for auto detect.
     * @var string 
     */
    public $language;
    
    /**
     * Effects in http://codepen.io/dimsemenov/pen/GAIkt
     * @var string 
     * ***** LIST OF Avilable value
     * 'fade', 'with-zoom', 'zoom-in', 'newspaper',    'move-horizontal', 'move-from-top', '3d-unfold', 'zoom-out'
     */
    public $effect;
    
    /**
     * Alias for 'type' in option;
     * 
     * <li>ajax</li>
     * <li>iframe</li>
     * <li>image</li>
     * <li>inline</li>
     * 
     * @var type string
     */
    public $type;
    
    /**
     * @var string asset class
     */
    public $asset = '\porcelanosa\magnificPopup\MagnificPopupAsset';
    
    /**
     * Run this widget.
     * This method registers necessary javascript.
     */
    public function run() {
        $effectList = ['fade', 'with-zoom', 'zoom-in', 'newspaper',
            'move-horizontal', 'move-from-top', '3d-unfold', 'zoom-out'
        ];
        
        if ($this->effect && in_array($this->effect, $effectList)) {
            $this->defaultOptions['mainClass'] = 'mfp-' . $this->effect;
            $this->defaultOptions['removalDelay'] = 500;
            $this->defaultOptions['callbacks'] = array(
                'beforeOpen' => new JsExpression("function() {this.st.image.markup = this.st.image.markup.replace('mfp-figure', 'mfp-figure mfp-with-anim');}")
            );
            if ($this->effect == 'with-zoom') {
                $this->defaultOptions = array_merge($this->defaultOptions, array(
                    'zoom' => array(
                        'enabled' => true,
                    ),
                ));
            }
        }
        
        if ($this->type !== null) {
            $this->options['type'] = $this->type;
        }
        
        $view = $this->getView();
        $asset = $this->asset;
        
        $options = array_merge($this->defaultOptions, $this->options);
        $js = "jQuery('{$this->target}').magnificPopup(" . Json::encode($options) . ");";
        
        $asset::register($view);
        $view->registerJs($js);
    }
}
