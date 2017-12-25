<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace daxslab\taggedview;

use Yii;
use yii\web\View as BaseView;

/**
 * Description of SeoTags
 *
 * @author glpz
 */
class View extends BaseView
{

    // toogle tags to register
    public $registerStandardTags = true;
    public $registerOpenGraphTags = true;
    public $registerTwitterCardTags = true;
    // toogle translations
    public $translate = ['title', 'site_name', 'description', 'author', 'keywords'];
    //meta properties
    public $author;
    public $site_name;
    public $url;
    public $description;
    public $type;
    public $locale;
    public $image;
    public $robots;
    public $keywords = [];
    public $creator;
    public $generator;
    public $date;
    public $data_type;
    public $card;
    public $site;
    public $label1;
    public $data1;
    public $label2;
    public $data2;

    private $updated_time;

    public function init()
    {
        if ($this->site_name == null) {
            $this->site_name = Yii::$app->name;
        }
        if ($this->url == null) {
            $this->url = Yii::$app->request->absoluteUrl;
        }

        $this->translateProperties();
    }

    protected function renderHeadHtml()
    {
        if ($this->locale == null) {
            $this->locale = str_replace('-', '_', Yii::$app->language);
        }

        if ($this->registerStandardTags) {
            $this->registerStandardMetaTags();
        }

        if ($this->registerOpenGraphTags) {
            $this->registerOpenGraphMetaTags();
        }

        if ($this->registerTwitterCardTags) {
            $this->registerTwitterCardMetaTags();
        }

        array_multisort($this->metaTags);
        return parent::renderHeadHtml();
    }

    protected function registerStandardMetaTags()
    {

        foreach ($this->keywords as $keyword) {
            $this->registerMetaTag(['name' => 'article:tag', 'content' => trim($keyword)]);
        }

        $this->keywords = empty($this->keywords) ? null : join(', ', $this->keywords);
        foreach (['author', 'description', 'robots', 'keywords', 'generator'] as $property) {
            if ($this->$property) {
                $this->registerMetaTag(['name' => $property, 'content' => $this->$property]);
            }
        }

        $this->registerLinkTag(['rel' => 'canonical', 'href' => $this->url]);

    }

    protected function registerOpenGraphMetaTags()
    {

        $this->updated_time = $this->date;

        foreach (['title', 'url', 'site_name', 'type', 'description', 'locale', 'updated_time'] as $property) {
            if ($this->$property) {
                $this->registerMetaTag(['property' => "og:" . $property, 'content' => $this->$property]);
            }

        }

        if ($this->image !== null) {
            if (is_array($this->image)) {
                foreach ($this->image as $key => $value) {
                    $this->registerMetaTag(['property' => 'og:image', 'content' => $value], 'og:image' . $key);
                }
            } else {
                $this->registerMetaTag(['property' => 'og:image', 'content' => $this->image], 'og:image');
            }

        }
    }


    protected function registerTwitterCardMetaTags()
    {
        foreach ([
                     'card',
                     'title',
                     'url',
                     'creator',
                     'site',
                     'type',
                     'description',
                     'label1',
                     'data1',
                     'label2',
                     'data2'
                 ] as $property) {
            if ($this->$property) {
                $this->registerMetaTag(['name' => "twitter:" . $property, 'content' => $this->$property]);
            }
        }

        if ($this->image !== null) {
            if (is_array($this->image)) {
                $this->registerMetaTag(['name' => 'twitter:image', 'content' => $this->image[0]], 'twitter:image');
            } else {
                $this->registerMetaTag(['name' => 'twitter:image', 'content' => $this->image], 'twitter:image');
            }
        }
    }

    protected function translateProperties()
    {
        foreach ($this->translate as $property) {
            if ($this->$property != null) {
                if (is_array($this->$property)) {
                    $the_array = $this->$property;
                    foreach ($the_array as $i => $word) {
                        $the_array[$i] = Yii::t('app', $word);
                    }
                    $this->$property = $the_array;
                } else {
                    $this->$property = Yii::t('app', $this->$property);
                }
            }
        }
    }

}