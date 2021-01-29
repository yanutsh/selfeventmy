<?

namespace app\components\menu;

use yii\base\Widget;
use yii\helpers\Html;
//use yii\bootstrap\Nav;
use yii\widgets\Menu;

class MenuWidget extends Widget
{

    public $type;
    public $options;
    public $slides;

//    public $view='index';

    public function init()
    {
        parent::init();
        $defaultOptions = [
            'class' => 'menu',
//            'encode' => false,
        ];
        if($this->options)
            $this->options  = array_merge($defaultOptions, $this->options);
        else
            $this->options  = $defaultOptions;
    }

    public function run()
    {
        assets\MenuAsset::register($this->view);
        $class = '\app\models\Navigation' . $this->type;
        $model = $class::find()->where(['parent' => 0])->orderBy(['[[order]]' => SORT_ASC])->all();


        $items = [];
        foreach($model as $m)
        {
            $children = $class::find()->where(['parent' => $m->id])->orderBy(['order' => SORT_ASC])->all();


            $subItems = [];
            foreach($children as $child)
            {
                $curUrl     = explode('/', trim(\Yii::$app->request->absoluteUrl, '/'));
                $active     = strpos(array_pop($curUrl), $child->link) !== false && $child->link != '/' || \Yii::$app->request->absoluteUrl == \Yii::$app->request->getHostInfo() . $child->link;
                $subItems[] = [
                    'label'       => (isset($child->image) && $child->image) ? Html::tag('span', Html::img($child->image), ['class' => 'image']) . Html::tag('span', $child->title, ['class' => 'text']) : $child->title,
                    'url'         => $active || !$child->link ? false : $child->link,
                    'active'      => $active,
                    'linkOptions' => [
                        'rel'    => $child->nofollow ? 'nofollow' : false,
                        'target' => $child->blank ? '_blank' : false,
                    ],
                ];
            }
        

            $curUrl = explode('/', trim(\Yii::$app->request->absoluteUrl, '/'));
            $active = strpos(array_pop($curUrl), $m->link) !== false && $m->link != '/' || \Yii::$app->request->absoluteUrl == \Yii::$app->request->getHostInfo() . $m->link;
            
            if($active)
            {
                if($subItems)
                    $items[] = [
                        'label'           => (isset($m->image) && $m->image) ? Html::tag('span', Html::img($m->image), ['class' => 'image']) . Html::tag('span', $m->title, ['class' => 'text']) : $m->title,
                        'active'          => $active,
                        'linkOptions'     => [
                            'rel'    => $m->nofollow ? 'nofollow' : false,
                            'target' => $m->blank ? '_blank' : false,
                        ],
                        'items'           => $subItems,
                        'dropDownOptions' => [
                            'options' => [
                                'class' => false,
                            ],
                        ],
                    ];
                else
                    $items[] = [
                        'label'           => (isset($m->image) && $m->image) ? Html::tag('span', Html::img($m->image), ['class' => 'image']) . Html::tag('span', $m->title, ['class' => 'text']) : $m->title,
                        'active'          => $active,
                        'linkOptions'     => [
                            'rel'    => $m->nofollow ? 'nofollow' : false,
                            'target' => $m->blank ? '_blank' : false,
                        ],
                        'dropDownOptions' => [
                            'options' => [
                                'class' => false,
                            ],
                        ],
                    ];
            }
            else
            {
                if($subItems)
                    $items[] = [
                        'label'           => (isset($m->image) && $m->image) ? Html::tag('span', Html::img($m->image), ['class' => 'image']) . Html::tag('span', $m->title, ['class' => 'text']) : $m->title,
                        'url'             => $active || !$m->link ? false : $m->link,
                        'active'          => $active,
                        'linkOptions'     => [
                            'rel'    => $m->nofollow ? 'nofollow' : false,
                            'target' => $m->blank ? '_blank' : false,
                        ],
                        'items'           => $subItems,
                        'dropDownOptions' => [
                            'options' => [
                                'class' => false,
                            ],
                        ],
                    ];
                else
                    $items[] = [
                        'label'           => (isset($m->image) && $m->image) ? Html::tag('span', Html::img($m->image), ['class' => 'image']) . Html::tag('span', $m->title, ['class' => 'text']) : $m->title,
                        'url'             => $active || !$m->link ? false : $m->link,
                        'active'          => $active,
                        'linkOptions'     => [
                            'rel'    => $m->nofollow ? 'nofollow' : false,
                            'target' => $m->blank ? '_blank' : false,
                        ],
                        'dropDownOptions' => [
                            'options' => [
                                'class' => false,
                            ],
                        ],
                    ];
            }
        }

//        $nav = Nav::begin([
//                    'items'           => $items,
//                    'id'              => false,
//                    'options'         => [
//                        'class' => $this->options['class'],
//                    ],
//                    'dropDownCaret'   => '',
////                    'dropdownClass'   => false,
//                    'activateParents' => true,
//        ]);
//        Html::removeCssClass($nav->options, 'nav');
//        foreach( $nav->items as $k => $it )
//        {
//            Html::removeCssClass($nav->items[$k]->options, 'dropdown');
//        }
//        $nav->end();
//        print_r($items);

        if($this->slides)
        {
            $ul = [];
            foreach($items as $item)
            {
                $ul[] = [
                    'content' => Html::a($item['label'], isset($item['url']) ? $item['url'] : '', $item['linkOptions'])
                ];
            }
            echo \bupy7\flexslider\FlexSlider::widget([
                'items'         => $ul,
                'pluginOptions' => [
                    "animation"     => 'slide',
                    "slideshow"     => false,
                    "controlNav"    => false,
            //  "customDirectionNav" => new \yii\web\JsExpression("$('.direction a')"),
                    "itemWidth"     => $this->slides['width'],
                    "itemMargin"    => 0,
                    "move"          => 1,
                    "minItems"      => 1,
                    "maxItems"      => 5,
                    "prevText"      => "", //\rmrevin\yii\fontawesome\FA::icon('angle-left'),
                    "nextText"      => "", //\rmrevin\yii\fontawesome\FA::icon('angle-right'),
                    'animationLoop' => false,
                ],
            //  'slidesOptions' => $this->options,
            ]);
        }
        else
            //debug($items);
            echo Menu::widget([
                'items'           => $items,
                'id'              => true, // false,
                'options'         => [
                    'class' => $this->options['class'],
                ],
                'activateParents' => true,
                'encodeLabels'    => false,
            ]);
    }

}
