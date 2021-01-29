<?php

namespace app\models;

use Yii;
use himiklab\sitemap\behaviors\SitemapBehavior;
use yii\helpers\Url;
use app\helpers\Transliteration;

/**
 * This is the model class for table "{{%page}}".
 *
 * @property integer $id
 * @property string $name
 * @property integer $parent
 * @property string $url
 * @property string $template
 * @property string $background 
 * @property string $h1
 * @property string $seo_title
 * @property string $seo_keywords
 * @property string $seo_description
 * @property string $content
 * @property integer $status
 * @property string $lastmod 
 * @property string $widget_list
 * @property integer $exclude_sitemap
 */
class Page extends \yii\db\ActiveRecord {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return '{{%page}}';
    }

    public function behaviors() {
        return [
            'sitemap' => [
                'class' => SitemapBehavior::className(),
                'scope' => function ($model) {
                    /** @var \yii\db\ActiveQuery $model */
                    $model->select(['id', 'url', 'lastmod'])->where(['exclude_sitemap' => 0]);
                },
                'dataClosure' => function ($model) {
                    if ($model->url == 'main')
                        return [
                            'loc' => '/',
                            'lastmod' => strtotime($model->lastmod),
                            'changefreq' => SitemapBehavior::CHANGEFREQ_DAILY,
                            'priority' => 1
                        ];
                    /** @var self $model */
                    return [
                        'loc' => Url::to(['/page/frontend', 'id' => $model->id]),
                        'lastmod' => strtotime($model->lastmod),
                        'changefreq' => SitemapBehavior::CHANGEFREQ_DAILY,
                        'priority' => 0.8
                    ];
                }
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['content'], 'string'],
            [['name', 'h1', 'seo_title', 'seo_keywords', 'seo_description',], 'string', 'max' => 1023],
            [['url', 'template'], 'string', 'max' => 255],
            [['status', 'lastmod'], 'safe'],
            [['parent', 'exclude_sitemap'], 'integer'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'id' => 'ID',
            'name' => 'Заголовок',
            'parent' => 'Родительская страница',
            'url' => 'Url',
            'template' => 'Шаблон',
            'h1' => 'H1',
            'seo_title' => 'SEO Title',
            'seo_keywords' => 'SEO Keywords',
            'seo_description' => 'SEO Description',
            'content' => 'Текст',
            'status' => 'Статус',
            'exclude_sitemap' => 'Исключить из карты сайта',
        ];
    }

    public function beforeSave($insert) {
        if (parent::beforeSave($insert)) {
            $this->lastmod = date('Y-m-d');
            if ($insert) {
                if (!$this->h1) {
                    $this->h1 = $this->name;
                }
            }
            if (!$this->url) {
                $this->url = Transliteration::url($this->name);
            }
            $this->url = Transliteration::url($this->url);
            $i = 0;
            if ($insert) {
                while ($pu = self::find()->where(['url' => $this->url . ($i ? '-' . $i : '')])->one()) {
                    $i++;
                }
            } else {
                while ($pu = self::find()->where(['url' => $this->url . ($i ? '-' . $i : '')])->andWhere(['not', ['id' => $this->id]])->one()) {
                    $i++;
                }
            }
            if ($i) {
                $this->url .= '-' . $i;
            }
            if (!$this->exclude_sitemap)
                $this->exclude_sitemap = 0;
            return true;
        }
        else {
            return false;
        }
    }

}
