TaggedView
==========

[![Build Status](https://secure.travis-ci.org/daxslab/yii2-taggedview.png)](http://travis-ci.org/daxslab/yii2-taggedview)
[![Latest Stable Version](https://poser.pugx.org/daxslab/yii2-taggedview/v/stable.svg)](https://packagist.org/packages/daxslab/yii2-taggedview)
[![Total Downloads](https://poser.pugx.org/daxslab/yii2-taggedview/downloads)](https://packagist.org/packages/daxslab/yii2-taggedview)
[![Latest Unstable Version](https://poser.pugx.org/daxslab/yii2-taggedview/v/unstable.svg)](https://packagist.org/packages/daxslab/yii2-taggedview)
[![License](https://poser.pugx.org/daxslab/yii2-taggedview/license.svg)](https://packagist.org/packages/daxslab/yii2-taggedview)

Extension to help setup the standard HTML meta tags besides the ones defined by Opengraph and TwitterCard to contribute to website SEO

Installation
------------

The preferred way to install this extension is through [composer](http://getcomposer.org/download/).

Either run

```
php composer.phar require --prefer-dist daxslab/yii2-taggedview "*"
```

or add

```
"daxslab/yii2-taggedview": "*"
```

to the require section of your `composer.json` file.

Configuration
-------------

Configure the View component into the main configuration file of your application:

```php
'components' => [
    //...
    'view' => [
        'class' => 'daxslab\taggedview\View',
        //configure some default values that will be shared by all the pages of the website
        //if they are not overwritten by the page itself
        'image' => 'http://domain.com/images/default-image.jpg',
    ],
    //...
]
```
    
    
Defaults
--------

The component will try to set some properties by default:

```php
$this->site_name = Yii::$app->name;
$this->url = Yii::$app->request->baseUrl;
$this->locale = Yii::$app->language;
```
    
You can overwrite the content of this tags in every page or in the component configuration. 

Usage
-----

Once the extension is configured, simply use it in your views by:

```php
<?php 
    $this->title = 'page title';
    $this->description = 'page description';
    $this->keywords = ['keywords', 'for', 'this', 'page'];
    $this->image = 'http://domain.com/images/page-image.jpg'; 
?>
```

Proudly made by [Daxslab](http://daxslab.com).