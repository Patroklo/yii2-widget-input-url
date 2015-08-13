<?php

namespace cyneek\yii2\widget\urlparser;

use yii\web\AssetBundle;

/**
 * Asset bundle for UrlParser Widget
 *
 * @author Joseba Juaniz <joseba.juaniz@gmail.com>
 * @since 1.0
 */
class UrlParserAsset extends AssetBundle
{

    public $depends = [
        'yii\web\JqueryAsset'
    ];

    public function init()
    {
        $this->sourcePath = __DIR__ . '/assets';

        $this->js[] = (YII_DEBUG ? 'js/urlparser.js' : 'js/urlparser.min.js');

        parent::init();
    }
}
