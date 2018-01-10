TaggedView
==========
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

    'components' => [
        ...
        'view' => [
            'class' => 'daxslab\taggedview\View',
            //configure some default values that will be shared by all the pages of the website
            //if they are not overwritten by the page itself
            'image' => 'http://domain.com/images/default-image.jpg',
        ],
        ...
    ]

Usage
-----

Once the extension is configured, simply use it in your views by:

    <?php 
    $this->title = 'page title';
    $this->description = 'page description';
    $this->keywords = 'page keywords';
    $this->image = 'http://domain.com/images/page-image.jpg'; 
    ?>