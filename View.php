<?php

/**
 * @copyright Gabriel Alejandro Lopez Lopez
 * @author Gabriel Alejandro Lopez Lopez <glpz@daxslab.com>
 * @license http://opensource.org/licenses/mit-license.php The MIT License (MIT)
 * @package yii2-taggedview
 */
namespace daxslab\taggedview;

use Yii;
use yii\web\View as BaseView;

/**
 * Tagged View for Yii2.
 * Helps setup the standard HTML meta tags besides the ones defined by Facebook's OpenGraph
 * and Twitter Cards in order to contribute to website SEO.
 *
 * @author Gabriel Alejandro Lopez Lopez <glpz@daxslab.com>
 */
class View extends BaseView
{

    /**
     * @var bool toggles if the component will register the standard HTML tags.
     */
    public $registerStandardTags = true;

    /**
     * @var bool toggles if the component will register the Facebook's OpenGraph tags.
     */
    public $registerOpenGraphTags = true;

    /**
     * @var bool toggles if the component will register the Twitter Cards tags.
     */
    public $registerTwitterCardTags = true;

    /**
     * @var array specifies which properties the component should try to translate.
     */
    public $translate = ['title', 'site_name', 'description', 'author', 'keywords'];

    /**
     * @var string specifies the current page content's author.
     */
    public $author;

    /**
     * @var string specifies the website name
     * It is set to Yii::app->name by default
     * @see yii\base\Application::$name
     */
    public $site_name;

    /**
     * @var string specifies the current page url.
     * Normally the component will set this with Yii::$app->request->absoluteUrl
     * @see yii\web\Request::$absoluteUrl
     */
    public $url;

    /**
     * @var string description of the current page.
     * It's normally used for the small portion of text that Google shows on the SERP,
     * and Facebook or Twitter under the title of the shared content.
     */
    public $description;

    public $type;

    public $locale;

    /**
     * @var string image that will be used to represent the current page.
     * Facebook and Twitter attach this image to the shared content.
     */
    public $image;

    public $robots;

    /**
     * @var array keywords for the current page.
     */
    public $keywords = [];

    public $creator;

    /**
     * @var string software used to create the current page.
     * By default set to our favorite one ;-)
     */
    public $generator = "Yii2 PHP Framework (www.yiiframework.com)";

    public $date;

    public $data_type;

    public $card;

    /**
     * @var string specifies the website name
     * This one is used by Twitter. It is set to Yii::app->name by default
     * @see yii\base\Application::$name
     */
    public $site;

    public $label1;

    public $data1;

    public $label2;

    public $data2;

    private $updated_time;

    /**
     * Sets some basic metatags according to app configuration if they have no been
     * set in the main configuration.
     */
    public function init()
    {
        parent::init();

        if ($this->site_name == null) {
            $this->site_name = Yii::$app->name;
            $this->site = Yii::$app->name;
        }
        if ($this->url == null) {
            $this->url = Yii::$app->request->absoluteUrl;
        }
        if ($this->date == null){
            $this->date = Yii::$app->formatter->asDatetime(time());
        }

        $this->translateProperties();
    }

    /**
     * @inheritdoc
     */
    protected function renderHeadHtml()
    {

        if(!Yii::$app->request->isAjax){
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
        }

        array_multisort($this->metaTags);
        return parent::renderHeadHtml();
    }

    /**
     * Registers the standard HTML metatags.
     */
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

    /**
     * Registers the Facebook's OpenGraph metatags.
     */
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


    /**
     * Registers the Twitter Cards metatags.
     */
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

    /**
     * Executes the translation tool to find out if there is a translation registered for
     * the original property value
     */
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
